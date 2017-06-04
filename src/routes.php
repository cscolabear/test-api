<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return 'Welcome Test-API';
});

// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

//
// 避免初階使用者不懂，先不使用 restful
//

/**
 * 取得文章列表
 * get parames: page: 1~N, amount: 1~N
 * @return  string    json string
 */
$app->get('/get/article_list', function ($request, $response, $args) {
    $params = $request->getQueryParams();
    $page = $params['page'] ?? 1;
    $amount = $params['amount'] ?? \Src\Models\Article::AMOUNT;

    $this->db;
    $article = new \Src\Models\Article;
    $json_string = $article->getList($page, $amount)->toJson();
    return $response->withHeader('Content-type', 'application/json')
            ->write($json_string);
});

/**
 * 取得指定 id 之文章
 * e.g. /get/article/2 or /get/article/3
 * @return  string    json string
 */
$app->get('/get/article/{id}', function ($request, $response, $args) {
    $arg_id = $args['id'] ?? null;
    $id = filter_var($arg_id, FILTER_SANITIZE_NUMBER_INT);
    if (! $id) {
        return $response->withStatus(400)->write("invalid input : {$arg_id}");
    }

    $this->db;
    $article = \Src\Models\Article::find($id);
    if ($article) {
        return $response->withHeader('Content-type', 'application/json')
            ->write( $article->toJson() );
    }

    return $response->withStatus(404)->write('page not found');
});

/**
 * 刪除指定 id 之文章
 * e.g. /delete/article/2 or /delete/article/3
 * @return  string    json string
 */
$app->get('/delete/article/{id}', function ($request, $response, $args) {
    $arg_id = $args['id'] ?? null;

    $this->db;
    $result = \Src\Models\Article::where('id', $arg_id)->delete();

    $response_ary = [
        'status' => 'deleted ' . ($result ? 'successfully' : 'failed'),
    ];

    return $response->withHeader('Content-type', 'application/json')
            ->write( json_encode($response_ary) );
});

/**
 * 還原刪除指定 id 之文章
 * e.g. /restore/article/2 or /restore/article/3
 * @return  string    json string
 */
$app->get('/restore/article/{id}', function ($request, $response, $args) {
    $arg_id = $args['id'] ?? null;

    $this->db;
    $result = \Src\Models\Article::where('id', $arg_id)->restore();

    $response_ary = [
        'status' => 'restore ' . ($result ? 'success' : 'failed'),
    ];

    return $response->withHeader('Content-type', 'application/json')
            ->write( json_encode($response_ary) );
});

/**
 * 新增文章
 * e.g. /add/article or /add/article
 * @return  string    json string
 */
$app->post('/add/article', function ($request, $response, $args) {
    $params = $request->getParsedBody();
    $inputs = [
        'title'       => $params['title'] ?? '',
        'description' => $params['description'] ?? '',
        'image'       => $params['image'] ?? '',
        'content'     => $params['content'] ?? '',
    ];

    $article = new \Src\Models\Article;
    $validations = $article->InputValidation($inputs);
    if (array_search(false, $validations) !== false) {
        $invalid_lsit = array_filter($validations, function($valid){
            return ! $valid; // 挑出未通過驗證
        });

        $response_ary = [
            'status' => 'invalid input',
            'fields' => $invalid_lsit,
        ];
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(400)->write( json_encode($response_ary) );
    }

    $this->db;
    $result = $article->create($inputs);
    $response_ary = [
            'status' => 'add article successful',
            'id' => $result->id,
        ];

    return $response->withHeader('Content-type', 'application/json')
        ->write( json_encode($response_ary) );
});

