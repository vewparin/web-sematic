<?php
      $KeywordString=' ';
      $label;
      $score;
class User
{       
    public $KeywordString=' shubham';
    public function analyze($id)
    {
        include 'database.php';
        $query = "select * from reviews1 where id='".$GLOBALS['id']."';";
        $r = pg_query($query);
        $row = pg_fetch_row($r);
        $this->analyzeSentiment($row);
        $this->analyzeKeyword($row);
    }

    public function analyzeSentiment($row){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gateway-lon.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2018-09-21');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"text\": \"$row[2]; \",\n  \"features\": {\n    \"sentiment\": {}\n  }\n}");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . 'NC5pFvhPQ1L0GnPXzcEhbtsc4FjJEQxBMvX8ZrQNY86A');
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) { echo 'Error:' . curl_error($ch); }
        $value=json_decode( $result, true );
        $GLOBALS['score']= $value['sentiment']['document']['score'];
        $GLOBALS['label']=$value['sentiment']['document']['label'];
        curl_close ($ch);                
    }
    public function analyzeKeyword($row){
                   $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://gateway-lon.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2018-09-21');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"text\": \" $row[2]\",\n  \"features\": {\n    \"keywords\": {}\n  }\n}");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . 'NC5pFvhPQ1L0GnPXzcEhbtsc4FjJEQxBMvX8ZrQNY86A');
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $keywordResult = curl_exec($ch);
            if (curl_errno($ch)) {
               echo 'Error:' . curl_error($ch);
            }
        $keywordValue=json_decode( $keywordResult, true );
        $keywrd1= $keywordValue['keywords'];

        foreach ($keywrd1 as $i => $value) {
                  $keywrd = $keywrd1[$i]['text'];
                  $GLOBALS['KeywordString'].=$keywrd.', ';
                }
            curl_close ($ch);       
        }

        public function database($score,$label,$KeywordString,$id){
                  $t=time();
      $timeStamp=date("Y-m-d",$t);
    $query = "insert into sentiments(review_id, sentiment, label, keywords, created_on) values('".$id."','".$score."','".$label."','".$KeywordString."','".$timeStamp."');";
              $r = pg_query($query);
        }
}

$users = new User;
if(isset($_GET["index"]))
{   
    $id = intval($_GET['index']);
    $users->analyze($id);
    echo "$KeywordString";
    echo "$score";
    echo "$label";
    echo "$id";
    $users->database($score,$label,$KeywordString,$id);
}

header("Location:http://localhost/websematic/sentiment.php");?>