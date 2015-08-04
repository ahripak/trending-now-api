<?php

namespace App\Http\Controllers;

use Codebird\Codebird as Codebird;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\Request as Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MainController extends BaseController
{
    /**
     * Method to send an ecapsulated JSON response.
     *
     * @param  array  $data
     * @param  integer $status
     * @return Illuminate\Http\JsonResponse
     */
    private function respond($data, $status = 200)
    {
        return response()->json(
            [
                'status' => $status,
                'data' => (array) $data,
            ]
        );
    }

    /**
     * API Method - Fetch [and cache] all trends.
     *
     * Route: /api/<version>/all
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $response = array(
            'twitter' => $this->unique(
                $this->twitter(),
                'name',
                (bool) Request::input('ensure_hash', false)
            ),
        );

        return $this->respond($response);
    }

    /**
     * Method to reduce the provided array to a unique set of items based on
     * a property or key of each item.
     *
     * @param  array   $list
     * @param  string  $property
     * @param  boolean $ensureHash
     * @return array
     */
    private function unique($list, $property, $ensureHash)
    {
        $final = [];
        $used  = [];

        foreach ($list as $item) {

            if (is_array($item)) {
                $item = (object) $item;
            }

            if (in_array($item->{$property}, $used)) {
                continue;
            }

            if ($ensureHash && substr($item->{$property}, 0, 1) !== '#') {
                $item->{$property} = '#' . $item->{$property};
            }

            $final[] = $item;
            $used[]  = $item->{$property};
        }

        return $final;
    }

    /**
     * Fetch trends from Twitter based on Global and USA scopes.
     *
     * @return array
     */
    private function twitter()
    {
        if (! $result = \Cache::get('twitter')) {
            Codebird::setConsumerKey(
                env('TWITTER_KEY'),
                env('TWITTER_SECRET')
            );

            $codeBird = Codebird::getInstance();

            $codeBird->setToken(
                env('TWITTER_TOKEN'),
                env('TWITTER_TOKEN_SECRET')
            );

            $usa   = (array) $codeBird->trends_place('id=23424977');
            $world = (array) $codeBird->trends_place('id=1');

            if (@$usa[0] && @$world[0]) {
                \Cache::put(
                    'twitter',
                    array_merge(
                        array_map(function($item) {
                            $item->type = 'usa';
                            return $item;
                        }, $usa[0]->trends),
                        array_map(function($item) {
                            $item->type = 'world';
                            return $item;
                        }, $world[0]->trends)
                    ), Carbon::now()->addMinutes(10)
                );

                $result = \Cache::get('twitter');
            } else {
                $result = array();
            }
        }

        return $result;
    }

    /**
     * Render the home page with the list of current trends.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return view('greeting', [
            'twitter' => $this->unique(
                $this->twitter(),
                'name',
                false
            ),
        ]);
    }
}
