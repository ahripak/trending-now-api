<?php

namespace App\Http\Controllers;

use Codebird\Codebird as Codebird;
use Carbon\Carbon as Carbon;
use Laravel\Lumen\Routing\Controller as BaseController;

class MainController extends BaseController
{
    private function respond($data, $status = 200)
    {
        return response()->json(
            [
                'status' => $status,
                'data' => (array) $data,
            ]
        );
    }
    public function all()
    {
        $response = array(
            'twitter' => $this->unique($this->twitter(), 'name'),
        );

        return $this->respond($response);
    }

    private function unique($list, $property)
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

            $final[] = $item;
            $used[]  = $item->{$property};
        }

        return $final;
    }

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

    public function index()
    {
        return view('greeting', [
            'twitter' => $this->unique($this->twitter(), 'name'),
        ]);
    }
}
