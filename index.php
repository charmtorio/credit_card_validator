<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <title>Credit Card Validator</title>
</head>
<body>
    <?php
        $success = 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['cc_number'])) {
                $ccno = str_replace(array('-', ' '), '', $_POST['cc_number']);

                if(!$ccno) {
                    $error = "Credit card number is required.";
                }
                else if(strlen($ccno) < 13) {
                    $error = "Credit card number must be atleast 13 digits.";
                }
                else if(!is_numeric($ccno)) {
                    $error = "Credit card number must be a number.";
                }

                if(!isset($error)) {
                    $last4 = substr($ccno, -4);

                    // reverse string
                    $rev_cc = strrev($ccno);
                    
                    // split string
                    $split_cc = str_split($rev_cc);

                    $output = lugn_algorithm($last4, $split_cc);

                    if($output[2] == 1) {
                        $card_name = what_card($ccno);
                    }
                }                
            }
        }

        function lugn_algorithm($last4, $split_cc) {
            $total = 0;
            $i = 1;
            foreach ($split_cc as $key => $value) {
                if($i % 2 == 0) {

                    $value *= 2;

                    // if sum is more than 9
                    if($value > 9) {
                        $value -= 9;
                    }
                }

                $total += $value;

                $i++;
            }
            
            // check if total is divisible by 10
            if($total % 10 == 0) {
                $message = "Credit card number ending in " . $last4. " is valid.";
                $alert = "success";
                $success = 1;
            }
            else {
                $message = "Credit card number ending in " . $last4. " is not valid.";
                $alert = "danger";
                $success = 0;
            }

            return array($message, $alert, $success);
        }

        function what_card($ccno)
        {
            $m = "This card is ";
            $cardtype = array(
                'amex'		=> '/^3[4|7]\\d{13}$/',
                'bankcard'	=> '/^56(10\\d\\d|022[1-5])\\d{10}$/',
                'diners'	=> '/^(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})$/',
                'discover'  => '/^(?:6011|650\\d)\\d{12}$/',
                'electron'	=> '/^(?:417500|4917\\d{2}|4913\\d{2})\\d{10}$/',
                'enroute'	=> '/^2(?:014|149)\\d{11}$/',
                'jcb'		=> '/^(3\\d{4}|2100|1800)\\d{11}$/',
                'maestro'	=> '/^(?:5020|6\\d{3})\\d{12}$/',
                'mc'		=> '/^5[1-5]\\d{14}$/',
                'solo'		=> '/^(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?$/',
                'switch'	=> '/^(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)$/',
                'visa'		=> '/^4\\d{12}(\\d{3})?$/',
                'voyager'	=> '/^8699[0-9]{11}$/'
            );

            if(preg_match($cardtype['amex'], $ccno)) {
                return $m .= ' an American Express.';
            }
            else if(preg_match($cardtype['bankcard'], $ccno)) {
                return $m .= ' a Bankcard.';
            }
            else if(preg_match($cardtype['diners'], $ccno)) {
                return $m .= ' a Diners Club.';
            }
            else if(preg_match($cardtype['discover'], $ccno)) {
                return $m .= ' a Discover.';
            }
            else if(preg_match($cardtype['electron'], $ccno)) {
                return $m .= ' an Electron.';
            }
            else if(preg_match($cardtype['enroute'], $ccno)) {
                return $m .= ' an Enroute.';
            }
            else if(preg_match($cardtype['jcb'], $ccno)) {
                return $m .= ' a JCB.';
            }
            else if(preg_match($cardtype['maestro'], $ccno)) {
                return $m .= ' a Maestro.';
            }
            else if(preg_match($cardtype['mc'], $ccno)) {
                return $m .= ' a Mastercard.';
            }
            else if(preg_match($cardtype['solo'], $ccno)) {
                return $m .= ' a Solo.';
            }
            else if(preg_match($cardtype['switch'], $ccno)) {
                return $m .= ' a Switch.';
            }
            else if(preg_match($cardtype['visa'], $ccno)) {
                return $m .= ' a Visa.';
            }
            else if(preg_match($cardtype['voyager'], $ccno)) {
                return $m .= ' a Voyager.';
            }
            else {
                return $m .= ' not on my list.';
            }
        }
    ?>

    <div class="container">
        <div class="sidenav">
            <div class="login-main-text">
                <h2>Credit Card <br>Validator</h2>
                <p>— Charm Torio</small></p>
            </div>
        </div>

        <div class="main">
            <div class="col-md-6 col-sm-12">
                <div class="cc-form">
                    <form action="index.php" method="post">
                        <div class="form-group">
                            <label>Credit Card Number *</label>
                            <input type="text" name="cc_number" id="cc_number" autocomplete="off" autofocus class="form-control">
                        </div>
                        <?php if(!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($output)) : ?>
                        <div class="alert alert-<?php echo $output[1]; ?>" role="alert">
                            <?php echo $output[0]; ?>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($card_name)) : ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $card_name; ?>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-black float-right">Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>