<?php
/*
  Project name : BITuN
  Start Date : 3 May, 2015 6:22:15 PM
  Author: Adarsh
  Purpose :
 */

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Bangalore Institute of Technology - The Unofficial website </title>
        <meta charset="utf-8">
        <meta name="Keywords" content="Question, College Review,College Comments , CET college,BIT,BIT-Bangalore, News, Chat, B.I.T, BIT AMA">
        <meta name="Description" content="Chat with people from BIT_Bangalore! Find answers to all your questions, doubts and queries about Bangalore Institute Of Technology.">
		<meta name="viewport" content="width=device-width" initial-scale=1>
        <link rel="stylesheet" type="text/css" href="Style_Folder/outline.css">
		<link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
		<?php
            include_once ('outline.php');
			ga();
		?>
    </head>
    <body>	
            <?php
            head();?>
            <section>
				<?php right(); ?>
                <div id="content">
					<h3>BIT Chat - Talk with people from college!</h3>
					<p>Ever wanted to talk to new people from college? Or do want to know anything about Bangalore Institute of Technology? This is your chance!
					Login with Facebook, Twitter, Google or Disqus and start chatting!
					</p>
					<div id="disqus_thread"></div>
<script type="text/javascript">
    var disqus_shortname = 'bitunofficial';
    var disqus_identifier = 'chat';
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
				</div> <!-- End of content div -->	
			</section>
        <?php footer(); ?> 
		<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script src="js/basic.js"></script>
    </body>
</html>	


