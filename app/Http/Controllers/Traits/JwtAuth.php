<?php


namespace App\Http\Controllers\Traits;

trait JwtAuth
{
    /**
     * Get authenticated user from request attributes.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed|null
     */
    protected function user()
    {
        return request()->attributes->get('auth');
    }
}
