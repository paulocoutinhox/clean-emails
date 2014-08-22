<?php

// functions
function debug($msg)
{
    echo("> $msg \n");
}

// vars
$file = null;

// filename
if (isset($argv) && is_array($argv) && isset($argv[1]))
{
    $file = $argv[1];
}
else 
{
    debug('ERROR: You need put filename as argument. Ex: clean-emails.php filename.txt');	
    exit;
}

// file exists
if (!file_exists($file))
{
	debug('ERROR: Your file not exists - ' . $file);	
	exit;
}

// list of emails to remove directly
$patternsToRemove  = array('klzlk.com', 'nepwk.com', 'pjjkp.com', 'sharklasers.com', 'mailinator.com', 'emailtemporario.com.br', 'jnxjn.com', 'mailmetrash.com', 'thankyou2010.com', 'trash2009.com', 'mt2009.com', 'trashymail.com', 'mytrashmail.com', '.mailexpire.com', 'mailexpire.com', 'jetable.org', 'tempemail.net', 'spamfree24.org', 'spamspot.com', 'tempalias.com', 'mailcatch.com', 'dsadsa.com', 'trashmail.com', 'africamail.com', 'myself.com');

// list of emails to correct
$patternsToCorrect = array();

$patternsToCorrect[] = array('correct' => 'hotmail.com', 'wrongs' => array(
    'hotmai.com', 'homail.com', 'hotmal.com', 'hotimail.com', 'hotmailcom', 'hotmil.com', 'hotmaill.com', 'htomail.com', 'hotmial.com', 'htmail.com', 'hormail.com', 'hotmeil.com', 'rotmail.com', 'HORTMAIL.COM', 'hotail.com', 'otmail.com', 'hotmail.co', 'hotemail.com', 'homtail.com', 'hotmail.om', 'hotrmail.com', 'hoitmail.com', 'hootmail.com', 'hotmailo.com', 'hotmail.com.com', 'hotmail.cm', 'hotmail.con', 'hotmsil.com', 'hoymail.com', 'hotmaio.com', '!hotmail.com', 'hotmail.comn', 'hotmail.br', 'hot.mail.com', 'hotmaol.com',
));

$patternsToCorrect[] = array('correct' => 'gmail.com', 'wrongs' => array(
    'gmai.com', 'gamil.com', 'gmil.com', 'gmal.com', 'gmailcom',
));

$patternsToCorrect[] = array('correct' => 'yahoo.com.br', 'wrongs' => array(
    'yhaoo.com.br', 'yaho.com.br', 'yaoo.com.br', 'yhoo.com.br', 'yahool.com.br', 'yahoo.com.b', 'yaho.com.br', 'yaoo.com.br', 'yhoo.com.br', 'ahoo.com.br', 'yahoocom.br', 'yahoo.co.br',
));
	
$patternsToCorrect[] = array('correct' => 'yahoo.com', 'wrongs' => array(
    'yahool.com', 'yaoo.com', 'yaool.com', 'yaho.com', 'yaoo.com', 'yhoo.com', 'ahoo.com', 'yahoocom', 'yahoo.co',
));

// get number of lines
$linesBefore = shell_exec("cat $file | wc -l");

// process to remove invalid emails
debug('Starting removing invalid emails...');

$qty = 1;

foreach ($patternsToRemove as $index => $pattern)
{
    debug('Processing: ' . $qty . ' of ' . count($patternsToRemove) . '...');	
    
    $content = shell_exec("cat $file | grep @$pattern");
    
    if ($content)
    {
        debug('Pattern with results: ' . $pattern);
        debug('Removing lines with pattern: ' . $pattern);
        shell_exec("sed -i '/@$pattern/d' $file");    
        debug('Removed!');
    }    
    else 
    {
        debug('Pattern without results: ' . $pattern);	
    }
    
    $qty++;
}

// process to correct wrong email domains
debug('Starting changing wrong domains...');

$qty = 1;

foreach ($patternsToCorrect as $index => $pattern)
{
	debug('Processing: ' . $qty . ' of ' . count($patternsToCorrect) . '...');	
	
	$correct = $pattern['correct'];
	$wrongs  = $pattern['wrongs'];
	
	if (is_array($wrongs) && count($wrongs) > 0)	
	{
	    $qtyWrong = 1;
	    
	    foreach($wrongs as $wrong)
	    {
	    	debug('Changing the wrong domain: ' . $wrong . ' to ' . $correct . ' - ' . $qtyWrong . ' of ' . count($wrongs) . '...');	
	    	
			$content = shell_exec("cat $file | grep @$wrong");
			
			if ($content)
			{
				debug('E-mail domain with results: ' . $wrong);
			    debug('Changing e-mail domain to: ' . $correct);
			    shell_exec("sed -i 's/@$wrong/@$correct/g' $file");    
			    debug('Changed!');
			}    
			else 
			{
			    debug('E-mail domain without results: ' . $wrong);	
			}			    
			
			$qtyWrong++;
	    }
	}
	else 
	{
	    debug('Invalid wrong domains for: ' . $correct);	
	}
	
    $qty++;    
}

// get number of lines
$linesAfter = shell_exec("cat $file | wc -l");

// show summary and result
debug('Number of lines before cleanup: ' . (int)$linesBefore);
debug('Number of lines after cleanup: ' . (int)$linesAfter);

debug('SUCCESS - Your file is clean now!');