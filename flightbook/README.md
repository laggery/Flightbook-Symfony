flightbook
==========

A Symfony project created on December 20, 2015, 9:09 pm.

@hack 
swfitmailer for php 5.6

vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/StreamBuffer.php right before the "stream_socket_client" command at line 263:

$options['ssl']['verify_peer'] = FALSE;
$options['ssl']['verify_peer_name'] = FALSE;
