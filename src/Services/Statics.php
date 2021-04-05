<?php

namespace Paksuco\Statics\Services;

use Paksuco\Menu\MenuContainer;
use Paksuco\Statics\Models\StaticsCategory;
use Paksuco\Statics\Models\StaticsItem;
use Paksuco\Statics\Tables\StaticsCategoriesTable;

class Statics
{
    public function fillMenu(MenuContainer $menu, $category)
    {
        $category = StaticsCategory::where("slug", "=", $category)->first();
        $this->pushCategoryChildren($menu, $category);
    }

    public function pushCategory(MenuContainer $menu, StaticsCategory $category)
    {
        $menu->addItem($category->title, "#", "", function ($submenu) use ($category) {
            $this->pushCategoryChildren($submenu, $category);
        }, $category->order);
    }

    public function pushCategoryChildren(MenuContainer $menu, StaticsCategory $category)
    {
        $childCategories = $category->children;
        $childItems = $category->items;
        $children = $childCategories->concat($childItems)->sortBy("order");
        foreach ($children as $child) {
            if ($child instanceof StaticsCategory) {
                $this->pushCategory($menu, $child);
            } else {
                $this->pushItem($menu, $child);
            }
        }
    }

    public function pushItem(MenuContainer $menu, StaticsItem $item)
    {
        $menu->addItem($item->title, route("paksuco-statics.category.items.frontshow", ["item" => $item]), "", null, $item->order);
    }

    public function getCategories($parentSlug)
    {
        return StaticsCategory::where("slug", $parentSlug)->first()->children;
    }
}
