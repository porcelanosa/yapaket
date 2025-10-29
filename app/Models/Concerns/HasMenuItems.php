<?php

namespace App\Models\Concerns;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasMenuItems
 * @package App\Models\Concerns
 */
trait HasMenuItems
{
    /**
     * @return MorphMany
     */
    public function menuItems(): MorphMany
    {
        return $this->morphMany(MenuItem::class, 'menuable');
    }

    /**
     * Check if this model is linked to any menu items
     */
    public function hasMenuItems(): bool
    {
        return $this->menuItems()->exists();
    }
}
