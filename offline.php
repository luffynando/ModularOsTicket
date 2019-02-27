<?php
/*********************************************************************
    offline.php

    Offline page...modify to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require_once('client.inc.php');
if(is_object($ost) && $ost->isSystemOnline()) {
    @header('Location: index.php'); //Redirect if the system is online.
    include('index.php');
    exit;
}
$nav=null;
require(CLIENTINC_DIR.'header.inc.php');
?>
<div class="fullpage app blank sidebar-opened">
    <article class="content">
        <div class="error-card global">
            <div class="error-container">
            <?php
            if(($page=$cfg->getOfflinePage())) {
                echo $page->getBodyWithImages();
            } else {
                echo '<h2 class="error-title">'.__('Support Ticket System Offline').'</h1>';
            }
            ?>
            </div>
            <div class="error-container">
                <br>
                <a class="btn btn-primary" href="index.php">
                <i class="fa fa-angle-left"></i> Try Again</a>
            </div>
        </div>
    </article>
</div>
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
