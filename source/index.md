---
title: API Reference

language_tabs:
  - curl

toc_footers:
  - <a href='https://hripak.com'>Alexander Hripak</a>
  - <a href='http://github.com/tripit/slate'>Documentation Powered by Slate</a>

includes:
  - errors

search: false
---

# Introduction

Welcome to the Trending Now API! You can use our API to access information on the latest Twitter trends.

You can view code examples in the dark area to the right, and you can switch the programming language of the examples with the tabs in the top right.

This API documentation page was created with [Slate](http://github.com/tripit/slate). Feel free to edit it and use it as a base for your own API's documentation.

<aside class="notice">
No authentication protocol is implemented, just try not to thrash the endpoint(s) with requests.
</aside>

# Trends

## Get All Trends

```shell
curl "http://hashtagsnow.hripak.com/api/1.0/all"
```

> The above command returns JSON structured like this:

```json
{
  "status": 200,
  "data": {
    "twitter": [
      {
        "name": "#kitty",
        "query": "%23kitty",
        "url": "http://twitter.com/search?q=%23kitty",
        "promoted_content": null
      },
      {
        "name": "herekittykitty",
        "query": "herekittykitty",
        "url": "http://twitter.com/search?q=herekittykitty",
        "promoted_content": null
      }
    ]
  }
}
```

This endpoint retrieves all trends.

### HTTP Request

`GET http://hashtagsnow.hripak.com/api/1.0/all`

### Query Parameters

Parameter | Default | Description
--------- | ------- | -----------
ensure_hash | false | If set to true, the name property of each trend will always have a hash symbol first.

<aside class="success">
  The response for this endpoint is cached for 30 minutes.
</aside>
