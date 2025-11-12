<?php
namespace Funks;

class Transactions
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

    /** 
     * Function to get all transactions from specific date range
     * @param string $id_user: id_user
     * @param string $start_date: Start date
     * @param string $end_date: End date
     * @param string $type: "expense" or "income"
     */
    public function getByDateRange($id_user, $start_date, $end_date, $type = 'expense') {
        $validationResponse = $this->app['validate']->valid_transactions_dates($start_date, $end_date, $lang);
        if($validationResponse !== true){
            return $validationResponse;
        }

        return $this->app['bd']->fetchObject("
            SELECT 
                id,
                id_category,
                amount,
                description,
                transaction_date
            FROM transactions
            WHERE id_user = '".$id_user."'
            AND type = '".$type."'
            AND transaction_date BETWEEN '".$start_date."' AND '".$end_date."'
            ORDER BY transaction_date DESC
        ");
    }

    /** 
     * Function to get transaction by id
     * @param string $id_user
     * @param string $id_transaction
     * @param string $lang
     */
    public function getById($id_user, $id_transaction, $lang){
        $data = $this->app['bd']->fetchRow("
            SELECT 
                t.id,
                t.amount,
                t.description,
                t.transaction_date,
                c.id AS category_id,
                c.type AS category_type,
                c.name AS category_name,
                c.icon AS category_icon,
                c.color AS category_color
            FROM transactions t
            LEFT JOIN categories c ON t.id_category = c.id
            WHERE t.id_user = '".$id_user."' 
            AND t.id = '".$id_transaction."'
        ");

        if(!$data){
            return $this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_NOT_FOUND_FOR_USER", $lang);
        }

        return [
            'id' => $data->id,
            'category' => [
                'id' => $data->category_id,
                'type' => $data->category_type,
                'name' => $data->category_name,
                'icon' => $data->category_icon,
                'color' => $data->category_color
            ],
            'amount' => $data->amount,
            'description' => $data->description,
            'transaction_date' => $data->transaction_date
        ];
    }

    /** 
     * Function to delete a transaction
     * @param string $id_user
     * @param string $id_transaction
     */
    public function delete($id_user, $id_transaction, $lang){
        //Check if transaction exists if not is because is delete
        if($this->app['bd']->countRows("SELECT id FROM transactions WHERE id_user = '".$id_user."' AND id = '".$id_transaction."'")){
            
            //Deleting transaction
            if($this->app['bd']->query("DELETE FROM transactions WHERE id_user = '".$id_user."' AND id = '".$id_transaction."'")){
                return [ 'message' => $this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_DELETING_SUCCESS", $lang) ];
            } else{
                return $this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_DELETING_ERROR", $lang);
            }
        } else{
            return $this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_DELETING_NOT_FOUND", $lang);
        }
    }
}
