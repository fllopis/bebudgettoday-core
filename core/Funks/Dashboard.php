<?php
namespace Funks;
use Funks\Categories;

class Dashboard
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

    /** 
     * Function to get all categories with percentage from specific date range
     * @param string $id_user: id_user
     * @param string $start_date: Start date
     * @param string $end_date: End date
     */
    public function getAllCategoriesWithStats($id_user, $type, $start_date, $end_date) {
        //Default vars
        $response = [];
        $_categories = new Categories($this->app);

        // Get all categories for the user
        $categories = $_categories->getAll($id_user, $type);
        
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
}
