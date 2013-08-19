<?php

use Blog\Post\PostAlgorithm;
use Blog\Archive\Archive;

class HomeController extends BaseController {

    const POSTS_PER_PAGE = 9;

    /**
     * @param PostAlgorithm $postAlgorithm
     */
    public function __construct( PostAlgorithm $postAlgorithm )
    {
        $this->postAlgorithm = $postAlgorithm;
    }

    /**
     * @return Response
     */
    public function index()
	{
        $posts = $this->postAlgorithm->postState()->orderByPostDate('desc')->paginate(self::POSTS_PER_PAGE);

        $homeTitle = 'Recent tutorials';

		return View::make('home.index', compact('posts', 'homeTitle'));
	}

    /**
     * @return Response
     */
    public function search()
    {
        $keyword = Input::get('keyword', '');

        $posts = $this->postAlgorithm->postState()->search($keyword)->paginate(self::POSTS_PER_PAGE);

        $homeTitle = 'Searching tutorials with keyword: ' . $keyword;

        return View::make('home.index', compact('posts', 'homeTitle'));
    }

    /**
     * @param $year
     * @param $month
     * @return Response
     */
    public function archive( $year, $month = 0 )
    {
        $posts = $this->postAlgorithm->postState()->year($year);

        if($month) $posts = $posts->month($month)->paginate(self::POSTS_PER_PAGE);
        else       $posts = $posts->paginate(self::POSTS_PER_PAGE);

        $homeTitle = 'Tutorials in year: ' . $year;

        if($month) $homeTitle .= ' and month: ' . date('F', mktime(0, 0, 0, $month, 1, 0));

        return View::make('home.index', compact('posts', 'homeTitle'));
    }
}