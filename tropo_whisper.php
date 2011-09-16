<?php

function choiceFCN($event) {
    global $session;
    if ($event->value == "yes") {
        conference("1337");
    }
    else {
        $curl_handle=curl_init(); 
        curl_setopt($curl_handle,CURLOPT_URL,'https://api.tropo.com/1.0/sessions/'.$session.'/signals?action=signal&value=exit'); 
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2); 
        curl_exec($curl_handle); 
        curl_close($curl_handle);
        say("You declined, disconnecting");
    }
}

function badChoiceFCN($event) {
    say("I’m sorry,  I didn’t understand that. You can say yes or no");
}

if ($currentCall->callerID == null) {
    call("+14075550100");
    ask("Do you want to connect to ".$caller."?", array(
        "choices" => "yes, no",
        "timeout" => 10.0,
        "attempts" => 3,
        "onChoice" => "choiceFCN",
        "onBadChoice" => "badChoiceFCN"
        )
    );
}
else {
    $token = 'your_token';
    $callerID = $currentCall->callerID;
    $sessionID = $currentCall->sessionId; 
    $curl_handle=curl_init(); 
     
    curl_setopt($curl_handle,CURLOPT_URL,'http://api.tropo.com/1.0/sessions?action=create&token='.$token.'&caller='.$callerID.'&session='.$sessionID); 
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2); 
    curl_exec($curl_handle); 
    curl_close($curl_handle); 

    say("please hold while we connect your call");
    conference("1337", array(
        "allowSignals" => "exit"
        ));
    say("The recipient declined the call.  Goodbye.");
    }
?>