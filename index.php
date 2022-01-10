<form method="post" enctype="multipart/form-data">
    <select name="opt" id="opt" onchange="chgme()">
        <option value='0' <?= isset($_POST['opt']) && $_POST['opt']=='0'? 'selected' :'' ?> >Excel -> Notepad</option>
        <option value='1' <?= isset($_POST['opt']) && $_POST['opt']=='1'? 'selected' :'' ?> >Notepad -> Excel</option>
    </select>
    <br/><br/>
    <select name="sel">
        <option value='4' <?= isset($_POST['sel']) && $_POST['sel']=='4'? 'selected' :'' ?> >A -> D</option>
        <option value='5' <?= isset($_POST['sel']) && $_POST['sel']=='5'? 'selected' :'' ?> >A -> E</option>
    </select><br/><br/>
    <div id="opt0" style="display:<?=  isset($_POST['opt']) && $_POST['opt']=='1'? 'none':'block'  ?>" >
    <!-- style="display: none"  -->
        <input type='file' name='file' onchange="submit()" />
        
        <br/>
        <em>must be in csv</em>
    </div>
    
    <div id="opt1" style="display:<?= !isset($_POST['opt']) || (isset($_POST['opt']) && $_POST['opt']=='0')? 'none':'block'  ?>" >
        <input onchange="submit()" name="inp" />
    </div>
    
    <script>
        chgme =()=>{
            var inp = document.querySelector('#opt').value
            if(inp=='0'){
                document.querySelector('#opt0').style.display = 'block'
                document.querySelector('#opt1').style.display = 'none'
            }
            if(inp=='1'){
                document.querySelector('#opt1').style.display = 'block'
                document.querySelector('#opt0').style.display = 'none'
            }
        }
    </script>

    <?php
        function shmap($n){
            return $n.'.';
        }
        if(isset($_POST['opt']) && $_POST['opt']=='0'){
            $filename = $_FILES['file']['name']?? '';
            $tmpname = $_FILES['file']['tmp_name'] ?? '';
            $extension = pathinfo ( $filename , PATHINFO_EXTENSION);
            if($extension <> 'csv'){
                echo 'Invalid file';
                exit();
            }
            echo '<p></p>';
            $tmpname = $_FILES['file']['tmp_name'];
            $myArr =['A.    ','B.   ','C.   ','D.   ','E.   '];
            $row=0;
            // $col = 0;
            // $col = (int)$_POST['sel'];
            $col = (int)$_POST['sel'] + 2;
            $handle = fopen($tmpname, "r");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {  
                if($row> 0){
                // if($row == 0){
                //     $col = count($data);//
                // }else{
                    for ($c=0; $c < $col; $c++) {
                        if($c==0){
                            echo $data[$c]; ///question label
                        }else if($c < $col - 1){
                            echo $myArr[$c-1].$data[$c]; ///options
                        }else{
                            echo 'ANSWER: '.trim($data[$c]); ///
                            echo '<br/>';
                        }
                        // echo $data[$c] . "<br />\n";
                        echo '<br/>';
                    }
                }
                // $row++;
                // $num = count($data);
                // echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                
            }
            fclose($handle);
        }
        if(isset($_POST['opt']) && $_POST['opt']=='1'){
            ///convert notepad to excel....
            $alph =  isset($_POST['sel']) && $_POST['sel']=='4'?  ['A','B','C','D'] : ['A','B','C','D','E'];
            
            $alphmap = array_map("shmap", $alph);
            $alphmap[] = 'ANSWER:';

            if(isset($_POST['inp'])){
                // $sn = 1; $ans ='';$optnum=0;
                $arr = explode(' ',$_POST['inp']);
                // print_r($arr);//  exit;
                echo  isset($_POST['sel']) && $_POST['sel']=='4'?   
                    "<table cellpadding='2' cellspacing='5' border='1' style='border: 1px solid #ccc'><tr><td></td><td>A</td><td>B</td><td>C</td><td>D</td><td>Answer</td></tr><tr><td>"
                    :
                    "<table cellpadding='2' cellspacing='5' border='1' style='border: 1px solid #ccc'><tr><td></td><td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>Answer</td></tr><tr><td>" ;
                for($i = 0;$i< count($arr); $i++){
                    

                    if(in_array(trim($arr[$i]),$alphmap)){
                        echo '</td><td>';

                        echo $arr[$i+1];
                        if(trim($arr[$i]) == 'ANSWER:') echo '</td></tr><tr><td>';
                        $i++;
                        continue;
                    }else{
                        echo $arr[$i];
                    }

                    echo ' ';

                }
                // echo "</td><td>$ans</td></tr><table>";
                echo "</td></tr><table>";
            }
        }


    ?>
</form>
