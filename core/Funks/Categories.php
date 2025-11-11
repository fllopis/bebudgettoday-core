<?php
namespace Funks;

class Categories
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

    /** 
     * Function to create the expense and income default categories
     * @param string $id_user: id_user
     */
    public function createDefaultCategories($id_user){
        //Default vars
        $dateOfCreation = $this->app['tools']->datetime();
        $categoriesToCreate = [
            '0' => [
                'id_user' => $id_user,
                'type' => 'income',
                'name' => 'Salary',
                'icon' => '',
                'color' => '#35a989',
                'creation_at' => $dateOfCreation,
                'updated_at' => $dateOfCreation,
            ],
            '1' => [
                'id_user' => $id_user,
                'type' => 'expense',
                'name' => 'Home',
                'icon' => '',
                'color' => '#f16c69',
                'creation_at' => $dateOfCreation,
                'updated_at' => $dateOfCreation,
            ],
            '2' => [
                'id_user' => $id_user,
                'type' => 'expense',
                'name' => 'Car',
                'icon' => '',
                'color' => '#4c86b9',
                'creation_at' => $dateOfCreation,
                'updated_at' => $dateOfCreation,
            ],
            '3' => [
                'id_user' => $id_user,
                'type' => 'expense',
                'name' => 'Supermarket',
                'icon' => '',
                'color' => '#f5b225',
                'creation_at' => $dateOfCreation,
                'updated_at' => $dateOfCreation,
            ],
        ];

        foreach($categoriesToCreate as $categoryToCreate){
            $this->app['bd']->insert('categories', $categoryToCreate);
        }
    }
}
