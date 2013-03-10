<?php

class CategoriesWidget extends Widget
{
    static $title = "Blog Categories";
    static $cmsTitle = 'Blog Category Widget';
    static $description = 'Show a list of blog categories as links';

    static $db = array(
        "WidgetTitle" => "Varchar(255)",
        "ShowCount" => "Enum('Yes, No')"
    );

    static $defaults = array(
        "WidgetTitle" => "Blog Categories",
        "ShowCount" => "Yes"
    );

    public function getCMSFields()
    {
        return new FieldList(
            new TextField('WidgetTitle', 'Widget Title'),
            new DropdownField('ShowCount','Show Article Count', singleton('CategoriesWidget')->dbObject('ShowCount')->enumValues())
        );
    }

    public function Title()
    {
        return $this->WidgetTitle ? $this->WidgetTitle : self::$title;
    }

    /**
     * Count the categories
     * @return string
     */
    public function totalEntries($catID) {
        $count = DB::query("SELECT COUNT(*) FROM SiteTree_Live LEFT JOIN BlogEntry ON SiteTree_Live.ID = BlogEntry.ID LEFT JOIN BlogEntry_BlogCategories ON BlogEntry_BlogCategories.BlogEntryID = BlogEntry.ID LEFT JOIN BlogCategory ON BlogEntry_BlogCategories.BlogCategoryID = BlogCategory.ID WHERE BlogCategory.ID LIKE '" . $catID . "'")->value();
        return $count;
    }

    /**
     * Get the blog categories
     * @return ArrayList
     */
    public function getCategories()
    {
        $categories = new ArrayList;
        foreach(BlogCategory::get() as $category) {
            $data = array(
                'Title' => $category->Title,
                'Link' => '/blog/category/' . $category->URLSegment,
                'ShowCount' => $this->ShowCount,
                'Count' => $this->totalEntries($category->ID)
            );
            $categories->push(new ArrayData($data));
        }
        return $categories;
    }
}