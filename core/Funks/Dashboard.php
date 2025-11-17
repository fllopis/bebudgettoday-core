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

    /** 
     * Function to get the monthly summary
     * @param string $id_user: id_user
     * @param string $start_date: Start date
     * @param string $end_date: End date
     */
    public function getMonthSummary($id_user, $start_date, $end_date){
        //Default vars
        $response = new \stdClass();
        $response->income = 0;
        $response->expense = 0;
        $response->resume = 0;

        //Total incomes
        $result = $this->app['bd']->fetchRow("
            SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
            FROM 
                transactions
            WHERE 
            id_user = ".$id_user."
            AND transaction_date BETWEEN '".$start_date."' AND '".$end_date."'
        ");
        $response->income = $result->total_income ?? 0;
        $response->expense = $result->total_expense ?? 0;

        //The total of monthly summary
        $response->resume = $response->income - $response->expense;

        return $response;
    }

    /** 
     * Function to return the motivation message for user.
     * @param string $id_user: id_user
     * @param string $start_date: Start date
     * @param string $end_date: End date
     */
    public function getSavingsMessage($id_user, $start_date, $end_date, $lang, $currency){
        //Default vars
        $currencies = $this->app['tools']->getCurrencies();
        $symbol = "";
        $symbolPosition = "";
        $response = new \stdClass();
        $response->showMotivation = false;
        $response->message = "";
        
        foreach ($currencies as $currencyData) {
            if ($currencyData['code'] === strtoupper($currency)) {
                $symbol = $currencyData['symbol'];
                $symbolPosition = $currencyData['symbol_position'];
            }
        }

        //Last month start_date && end_date
        $startTimestamp = strtotime($start_date . ' -1 month');
        $start_date_lastMonth = date('Y-m-01', $startTimestamp);
        $end_date_lastMonth = date('Y-m-t', $startTimestamp);


        //Getting monthly summary for actual month and last month.
        $summaryActualMonth = $this->getMonthSummary($id_user, $start_date, $end_date);
        $amountActualMonth = (float) $summaryActualMonth->resume;

        //Summary from last month
        $summaryLastMonth = $this->getMonthSummary($id_user, $start_date_lastMonth, $end_date_lastMonth);
        $amountLastMonth = (float) $summaryLastMonth->resume;

        $amountLastMonth = 0;
        $amountActualMonth = 50;
        
        // Manejo de Saldo Cero o Nulo en el mes anterior
        if ($amountLastMonth == 0) {
            if ($amountActualMonth > 0) {
                $response->showMotivation = true;

                $translationProps = [
                    'amount' => number_format($amountActualMonth, 2),
                    'currency_init' => ($symbolPosition == 'start') ? $symbol : '',
                    'currency_end' => ($symbolPosition == 'end') ? $symbol : '',
                ];

                $response->message = $this->app['lang']->getTranslationStatic("DASHBOARD_POSITIVE_SALARY", $lang, $translationProps);
            } else{
                $response->message = $this->app['lang']->getTranslationStatic("DASHBOARD_NOT_DATA", $lang);
            }

            return $response;
        }

        //Calculate the variation in base to the last month.
        $variation = (($amountActualMonth - $amountLastMonth) / abs($amountLastMonth)) * 100;
        $variation_abs = round(abs($variation));

        //Show motivation message
        $response->showMotivation = true;
        
        //Generating the motivation message
        if ($variation > 0) {
            $response->message = $this->app['lang']->getTranslationStatic("DASHBOARD_POSITIVE_VARIATION", $lang, ['variation' => $variation_abs]);
        } elseif ($variation < 0) {
            $response->message = $this->app['lang']->getTranslationStatic("DASHBOARD_NEGATIVE_VARIATION", $lang, ['variation' => $variation_abs]);
        } else {
            $response->message = $this->app['lang']->getTranslationStatic("DASHBOARD_EQUAL_VARIATION", $lang);
        }

        return $response;
    }
}
