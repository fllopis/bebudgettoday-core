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

    /** 
     * Function to get all categories with percentage from specific date range
     * @param string $id_user: id_user
     * @param string $start_date: Start date
     * @param string $end_date: End date
     */
    public function getAllWithStats($id_user, $type, $start_date, $end_date) {
        //Default vars
        $response = [];

        // Get all categories for the user
        $categories = $this->getAll($id_user, $type);
        
        // Get total amount of transactions in the date range
        $totalResult = $this->app['bd']->fetchRow("
            SELECT SUM(amount) as total
            FROM transactions
            WHERE id_user = ".$id_user."
            AND type = '".$type."'
            AND transaction_date BETWEEN '".$start_date."' AND '".$end_date."'
        ");
        $totalAmount = $totalResult->total ?? 0;

        foreach ($categories as $category) {
            // Sum of transactions for the category in the date range
            $categoryTotalResult = $this->app['bd']->fetchRow("
                SELECT SUM(amount) as total
                FROM transactions
                WHERE id_user = ".$id_user."
                AND id_category = ".$category->id."
                AND transaction_date BETWEEN '".$start_date."' AND '".$end_date."'
            ");
            $categoryTotal = $categoryTotalResult->total ?? 0;

            // Calculate percentage
            $percentage = $totalAmount > 0 ? round(($categoryTotal / $totalAmount) * 100) : 0;

            $response[] = [
                'id'        => $category->id,
                'name'      => $category->name,
                'icon'      => $category->icon,
                'color'     => $category->color,
                'total'     => $categoryTotal,
                'percentage'=> $percentage
            ];
        }

        return $response;
    }

    /** 
     * Function to get all categories from specific type
     * @param string $id_user: id_user
     * @param string $type: "expense" or "income"
     */
    public function getAll($id_user, $type="expense"){
        return $this->app['bd']->fetchObject("SELECT id, type, name, icon, color FROM categories WHERE id_user = '".$id_user."' AND type = '".$type."'");
    }

    /** 
     * Function the total categories that has user
     * @param string $id_user: id_user
     * @param string $type: "expense" or "income"
     */
    public function getAllTotal($id_user, $type="expense"){
        return $this->app['bd']->countRows("SELECT id FROM categories WHERE id_user = '".$id_user."' AND type = '".$type."'");
    }

    /** 
     * Function to get category by id
     * @param string $id_user
     * @param string $id_category
     * @param string $lang
     */
    public function getById($id_user, $id_category, $lang){
        $data = $this->app['bd']->fetchRow("SELECT id, type, name, icon, color FROM categories WHERE id_user = '".$id_user."' AND id = '".$id_category."'");

        if(!$data){
            return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_NOT_FOUND_FOR_USER", $lang);
        }

        return $data;
    }

    /** 
     * Function to get category by id
     * @param string $id_user
     * @param string $id_category
     * @param string $lang
     */
    public function checkCategoryOnUser($id_user, $id_category){
        if($this->app['bd']->countRows("SELECT id FROM categories WHERE id_user = '".$id_user."' AND id = '".$id_category."'") > 0){
            return true;
        }

        return false;
    }

    /** 
     * Function to create or update a specific category
     * @param int $id_user
     * @param int $id_category
     * @param array $data
     * @param string $lang
     * @return object: With category data
     */
    public function manageCategory($id_user, $id_category, $data, $lang){
        //Limit of user
        $limitValidationResponse = $this->app['validate']->valid_checkCategoryLimit($id_user, $lang);
        if($limitValidationResponse !== true){
            return $limitValidationResponse;
        }

        //Category validations
        $validationResponse = $this->app['validate']->valid_category($id_category, $data, $lang);
        if($validationResponse !== true){
            return $validationResponse;
        }

        //Updating category
        $dataToManage               = [];
        $dataToManage['name']       = (isset($data['name'])) ? $data['name'] : "-";
        $dataToManage['icon']       = (isset($data['icon'])) ? $data['icon'] : "-";
        $dataToManage['color']      = (isset($data['color'])) ? $data['color'] : "-";

        $action = ($id_category != '0') ? 'update' : 'creation';

        switch ($action) {
            case 'creation':
                $dataToManage['id_user']        = $data['id_user'];
                $dataToManage['type']           = $data['type'];
                $dataToManage['creation_at']    = $this->app['tools']->datetime();
                $dataToManage['updated_at']     = $this->app['tools']->datetime();

                if($this->app['bd']->insert('categories', $dataToManage)){
                    $id_category = $this->app['bd']->lastId();
                    return $this->getById($id_user, $id_category);
                } else{
                    return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_CREATION_UNKNOW_ERROR", $lang);
                }
                break;
            case 'update':
                $dataToManage['updated_at'] = $this->app['tools']->datetime();

                if($this->app['bd']->update('categories', $dataToManage, ' id_user = "'.$id_user.'" AND id = "'.$id_category.'"')){
                    return $this->getById($id_user, $id_category);
                } else{
                    return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_UPDATE_UNKNOW_ERROR", $lang);
                }
                break;
        }

        return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_UNKNOW_ERROR", $lang);
    }

    /** 
     * Function to delete a category
     * @param string $id_user
     * @param string $id_category
     */
    public function delete($id_user, $id_category, $lang){
        //Check if category exists if not is because is delete
        if($this->app['bd']->countRows("SELECT id FROM categories WHERE id_user = '".$id_user."' AND id = '".$id_category."'")){
            
            //Deleting category
            if($this->app['bd']->query("DELETE FROM categories WHERE id_user = '".$id_user."' AND id = '".$id_category."'")){
                return [ 'message' => $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_DELETING_SUCCESS", $lang) ];
            } else{
                return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_DELETING_ERROR", $lang);
            }
        } else{
            return $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_DELETING_NOT_FOUND", $lang);
        }
    }
}
