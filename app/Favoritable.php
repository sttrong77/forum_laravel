<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    /**
     * A reply can be favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */

     protected static function bootFavoritable()
     {
         static::deleting(function ($model) {
             $model->favorites->each->delete();
         });
     }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite the current reply.
     *
     * @return Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite(){
      $attributes = ['user_id' => auth()->id()];

      $this->favorites()->where($attributes)->get()->each->delete(); //deleta em favorites e activites


    }

    public function isFavorited()
    {
        return ! ! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute(){ //$reply->isFavorited
      return $this->isFavorited();
    }

    /**
     * Get the number of favorites for the reply.
     *
     * @return integer
     */
    public function getFavoritesCountAttribute() //$reply->favoritesCount
    {
        return $this->favorites->count();
    }
}
