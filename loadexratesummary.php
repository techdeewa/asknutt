<?php


//http://www.settrade.com/C13_InvestorType.jsp

    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }

    // Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
            CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_HTTPHEADER => array('Content-type: "text/plain; charset=UTF-8"'),
        );

        $ch = curl_init();  // Initialising cURL
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }


        $scraped_page_raw = curl("https://www.bot.or.th/Thai/Statistics/FinancialMarkets/ExchangeRate/_layouts/Application/ExchangeRate/ExchangeRate.aspx");    // Downloading IMDB home page to variable $scraped_page
        $scraped_data = scrape_between($scraped_page_raw, "ctl00_PlaceHolderMain_dgAvg", "</table>");   // Scraping downloaded dara in $scraped_page for content between <title> and </title> tags

        echo $scraped_data;


        /*
        $scraped_data = iconv('TIS-620','UTF-8//ignore',$scraped_page_raw);
        $scraped_data = scrape_between($scraped_data, "<!-- Content -->", "<!-- End Content -->");   // Scraping downloaded dara in $scraped_page for content between <title> and </title> tags
        $scraped_data = scrape_between($scraped_data, "col-md-offset-1 col-md-10", "</div></div>");
        $scraped_data = substr($scraped_data,2);


          $summarydate =  scrape_between($scraped_data, "<strong>", "</strong>");

        $scraped_data = scrape_between($scraped_data, "table-responsive", "</table>");
        $scraped_data = substr($scraped_data,2);
        $scraped_data .=  "</table>";

        //instutie
        $instu_data = scrape_between($scraped_data, "<td>สถาบันในประเทศ</td>", "</tr>");
        //$instu_data = scrape_between($scraped_data, "<tbody>", "</tr>");

        //Broker
        $broker_data = scrape_between($scraped_data, "<td>บัญชีบริษัทหลักทรัพย์</td>", "</tr>");
        ////foreign
        $foriegn_data = scrape_between($scraped_data, "<td>นักลงทุนต่างประเทศ</td>", "</tr>");

        //Local
        $local_data = scrape_between($scraped_data, "<td>นักลงทุนทั่วไปในประเทศ</td>", "</tr>");

        //echo iconv('TIS-620','UTF-8//ignore',$scraped_data);
        //echo iconv('TIS-620','UTF-8//ignore',$scraped_data);
        //echo $scraped_data;

        echo $summarydate . "</br>";
        echo "สถาบันในประเทศ : " . $instu_data . "</br>";
        echo "บัญชีบริษัทหลักทรัพย์ : " . $broker_data . "</br>";
        echo "นักลงทุนต่างประเทศ : " . $foriegn_data . "</br>";
        echo "นักลงทุนทั่วไปในประเทศ : " . $local_data . "</br>";
*/

/*
        $newsresult = explode("<hr />",$scraped_data);
        //$result = json_encode($result);

        //echo $scraped_data; // Echoing $scraped data, should show "The Internet Movie Database (IMDb)"

        //echo $scraped_data;


        //var_dump($result);

        //echo $result[1];

        //$resd = str_replace("p","h",str_replace("h3","h",str_replace("h5","h",$result[1])));

        $result = "";

        for ($x = 1; $x <= 5; $x++) {

            $contentres = str_replace("<p>","<h>",str_replace("h3","h",str_replace("h5","h",$newsresult[$x])));

            $contentresd = explode("<h>",$contentres);

            //split link & topic
            $contentresdsplit = explode(">",str_replace("</h>","",$contentresd[1]));
            //echo "NEWS : " . $x . "</BR>";
            //echo "header : ". str_replace("</a","",$contentresdsplit[1]) . "</BR>";
            //echo "date : " . $contentresd[2] . "</BR>";
            //echo "link : " . str_replace("<a href=","",str_replace('"','',$contentresdsplit[0])) . "</BR>";
            //echo "==================================" . "</BR>";

            $result .= "NEWS : " . $x . "</BR>";
            $result .= "header : ". str_replace("</a","",$contentresdsplit[1]) . "</BR>";
            $result .= "date : " . $contentresd[2] . "</BR>";
            $result .= "link : " . str_replace("<a href=","",str_replace('"','',$contentresdsplit[0])) . "</BR>";
            $result .= "==================================" . "</BR>";

        }



        echo $result;

        //var_dump($resd1);

        //$result = "";
*/

?>
