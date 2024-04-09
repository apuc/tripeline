<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\OurTeam;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagePost extends Controller {


    /**
     * Show Pages.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function index( \Illuminate\Http\Request $request ) {
        $page    = explode( '/', $request->path() )[1];
        $content = Page::query()->where( [ [ 'slug', '=', $page ] ] )->first() ?? false;

        return view( 'pages/page', [
            'content' =>
                [
                    'slug'              => $content['slug'],
                    'embed_video'       => $content['embed_video'],
                    'title'             => $this->getTranslateContent( $content, 'title' ),
                    'body'              => ($this->getTranslateContent( $content, 'body' )),
                    'meta_title'        => $this->getTranslateContent( $content, 'meta_title' ),
                    'meta_keywords'     => $this->getTranslateContent( $content, 'meta_keywords' ),
                    'meta_descriptions' => $this->getTranslateContent( $content, 'meta_descriptions' )
                ]
        ] );
    }


    /**
     * Show about page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function company( \Illuminate\Http\Request $request ) {
        $content       = Page::query()->where( [ [ 'slug', '=', 'company' ] ] )->first() ?? false;
        $our_team      = OurTeam::query()->where( 'status', '=', 'enabled' )->get();
        $team_response = [];
        foreach ( $our_team as $team ) {
            $team_response[] = [
                'title'    => $this->getTranslateContent( $team, 'title' ),
                'position' => $this->getTranslateContent( $team, 'position' ),
                'body'              => strip_tags($this->getTranslateContent( $team, 'body' )),
                'image'    => $this->getImageBySize('130x130', $team['image'])
            ];
        }

        if ( $content ) {
            return view( 'pages/company', [
                'content' =>
                    [
                        'slug'              => $content['slug'],
                        'embed_video'       => $content['embed_video'],
                        'title'             => $this->getTranslateContent( $content, 'title' ),
                        'body'              => strip_tags($this->getTranslateContent( $content, 'body' )),
                        'meta_title'        => $this->getTranslateContent( $content, 'meta_title' ),
                        'meta_keywords'     => $this->getTranslateContent( $content, 'meta_keywords' ),
                        'meta_descriptions' => $this->getTranslateContent( $content, 'meta_descriptions' ),
                        'team_response'     => $team_response,

                    ]
            ] );
        }
        return view( 'pages/company', [
            'content' => []
        ] );
    }


    public function getTranslateContent( $content, $key ) {
        return ( isset( $content[ $key . '_' . app()->getLocale() ] ) && strlen( $content[ $key . '_' . app()->getLocale() ] ) > 0 ) ? $content[ $key . '_' . app()->getLocale() ] : $content[ $key . '_en' ];
    }

    public function getImageBySize($size, $image): string {
        try {
            $pathinfo = pathinfo($image);
            $pathinfo['basename'] = $size . '_' . $pathinfo['basename'];
            $resized_image = $pathinfo['dirname'] . '/' . $pathinfo['basename'];
        }catch (\Exception $e){
            return $image;
        }
        return $resized_image;
    }
}
