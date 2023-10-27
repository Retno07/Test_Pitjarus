<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class ChartController extends CI_Controller {  
    /** 
     * This method is used to get all the data. 
     * 
     * @It will return Response 
    */  
    public function __construct() {  
       parent::__construct();  
       $this->load->database();  
    //    $this->load->model('ChartModels');
    }   
    
    /** 
     * This method is used to get all the data. 
     * 
     * @It will return Response 
    */  
    public function index()  
    {  
          /** API ENDPOINT */
        $chart_url = "http://localhost:5000/api/getChart";
        $grid_url = "http://localhost:5000/api/getTable";

          /* INIT THE CURL SESSION*/
        $chart_curl = curl_init($chart_url);
        $grid_curl = curl_init($grid_url);

        /** SET CURL SESSION OPTION */
        curl_setopt($chart_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($grid_curl, CURLOPT_RETURNTRANSFER, true);

        /** EXECUTE THE CURL SESSION */
        $chart_response = curl_exec($chart_curl);
        $grid_response = curl_exec($grid_curl);
       
        /** CLOSE CURL SESSION */
        curl_close($chart_curl);
        curl_close($grid_curl);

        /** DECODE RESPONSE FROM CURL SESSION ABOVE */
        $chart_data = json_decode($chart_response, true);
        $grid_data = json_decode($grid_response, true);

        /** VALIDATE THE JSON RESPONSE */
        if (json_last_error() === JSON_ERROR_NONE) {

            /** VALIDATE THE DATA KEY FROM JSON RESPONSE */
            if (isset($chart_data['data'])) {

                /** IF THE DATA KEY IS PRESENT OR IS NOT NULL THEN 
                 * ASSIGN THE DATA VALUE INTO TOTALS
                 */
                $totals = array();
        
                
                foreach ($chart_data['data'] as $item) {
                    $totals[] = $item['total'];
                }
    
                /** encoding the $totals array into a JSON string, ensuring that any numeric strings in the array are encoded as numbers */
                $totals = json_encode($totals, JSON_NUMERIC_CHECK);
                $data['chart'] = $totals;
            } else {
                /** IF THE DATA KEY IS NOT PRESENT OR IS NULL */
                print_r("The 'data' key does not exist in the response.");
            }
        } else {
            /** IF THE JSON RESPONSE IS ERROR */
            print_r("Invalid JSON response.");
        }

        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($grid_data['data'])) {
                /** IF THE DATA KEY IS PRESENT OR IS NOT NULL THEN 
                 * ASSIGN THE DATA VALUE INTO BRAND_DATA
                 */
                $brand_data = $grid_data['data'];
                $brand_data_array = array();
    
               
                foreach ($brand_data as $item) {
                    $brand_data_array[] = json_decode(json_encode($item));
                }
    
                $data['grid'] = $brand_data_array;
            } else {
                print_r("The 'data' key does not exist in the response.");
            }
        } else {
            print_r("Invalid JSON response.");
        }
        $this->load->view('my_chart', $data);  
    }
}