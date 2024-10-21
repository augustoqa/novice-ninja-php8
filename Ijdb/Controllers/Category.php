<?php

namespace Ijdb\Controllers;

use Ninja\DatabaseTable;

class Category
{
    public function __construct(private DatabaseTable $categoriesTable)
    {
    }

    public function edit(?string $id = null)
    {
        $title = 'Add Category';
        if (isset($id)) {
            $title = 'Edit Category';
            $category = $this->categoriesTable->find('id', $id)[0];
        }

        return [
            'template' => 'editcategory.html.php',
            'title' => $title,
            'variables' => [
                'category' => $category ?? null
            ]
        ];
    }

    public function editSubmit()
    {
        $category = $_POST['category'];

        $this->categoriesTable->save($category);

        header('location: /category/list');
    }
}