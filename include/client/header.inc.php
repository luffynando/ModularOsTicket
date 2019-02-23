<?php
$title=($cfg && is_object($cfg) && $cfg->getTitle())
    ? $cfg->getTitle() : 'osTicket :: '.__('Support Ticket System');
$signin_url = ROOT_PATH . "login.php"
    . ($thisclient ? "?e=".urlencode($thisclient->getEmail()) : "");
$signout_url = ROOT_PATH . "logout.php?auth=".$ost->getLinkToken();

header("Content-Type: text/html; charset=UTF-8");
header("X-Frame-Options: SAMEORIGIN");
if (($lang = Internationalization::getCurrentLanguage())) {
    $langs = array_unique(array($lang, $cfg->getPrimaryLanguage()));
    $langs = Internationalization::rfc1766($langs);
    header("Content-Language: ".implode(', ', $langs));
}
?>
<!DOCTYPE html>
<html<?php
if ($lang
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . $lang . '"';
}
?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo Format::htmlchars($title); ?></title>
    <meta name="description" content="customer support platform">
    <meta name="keywords" content="osTicket, Customer support system, support ticket system">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo MODULAR_PATH; ?>css/vendor.css">
    <link rel="stylesheet" href="<?php echo MODULAR_PATH; ?>css/hawk.css">
    <!-- Theme initialization -->
    <script>
            var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {};
            var modularpath = "<?php echo MODULAR_PATH; ?>";
            var themeName = themeSettings.themeName || '';
            if (themeName)
            {
                document.write('<link rel="stylesheet" id="theme-style" href="' + modularpath + 'css/app-' + themeName + '.css">');
            }
            else
            {
                document.write('<link rel="stylesheet" id="theme-style" href="' + modularpath + 'css/app.css">');
            }
    </script>
	<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/osticket.css?d4e240b" media="screen"/>
    <!--<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/theme.css?d4e240b" media="screen"/>-->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/print.css?d4e240b" media="print"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/typeahead.css?d4e240b"
         media="screen" />
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css?d4e240b"
        rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/thread.css?d4e240b" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css?d4e240b" media="screen"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome.min.css?d4e240b"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css?d4e240b"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css?d4e240b"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css?d4e240b"/>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-16x16.png" sizes="16x16" />
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-3.3.1.min.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-ui-1.12.1.custom.min.js?d4e240b"></script>
    <script src="<?php echo ROOT_PATH; ?>js/osticket.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/filedrop.field.js?d4e240b"></script>
    <script src="<?php echo ROOT_PATH; ?>scp/js/bootstrap-typeahead.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor.min.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-plugins.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-osticket.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/select2.min.js?d4e240b"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/fabric.min.js?d4e240b"></script>
    <?php
    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }

    // Offer alternate links for search engines
    // @see https://support.google.com/webmasters/answer/189077?hl=en
    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
        && (count($all_langs) > 1)
    ) {
        $langs = Internationalization::rfc1766(array_keys($all_langs));
        $qs = array();
        parse_str($_SERVER['QUERY_STRING'], $qs);
        foreach ($langs as $L) {
            $qs['lang'] = $L; ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?<?php
            echo http_build_query($qs); ?>" hreflang="<?php echo $L; ?>" />
<?php
        } ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
            hreflang="x-default" />
<?php
    }
    ?>
</head>
<body>
    <div class="main-wrapper">
        <div class="app" id="app">
            <header class="header">
                <div class="header-block header-block-collapse d-lg-none d-xl-none">
                    <button class="collapse-btn" id="sidebar-collapse-btn">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                <div class="header-block header-block-search">
                </div>
                <div class="header-block header-block-buttons">
                    <a href="https://github.com/modularcode/modular-admin-html" class="btn btn-sm header-btn">
                        <i class="fa fa-github-alt"></i>
                        <span>View on GitHub</span>
                    </a>
                    <a href="https://github.com/modularcode/modular-admin-html/stargazers" class="btn btn-sm header-btn">
                        <i class="fa fa-star"></i>
                        <span>Star Us</span>
                    </a>
                    <a href="https://github.com/modularcode/modular-admin-html/releases" class="btn btn-sm header-btn">
                        <i class="fa fa-cloud-download"></i>
                        <span>Download .zip</span>
                    </a>
                </div>
                <div class="header-block header-block-nav">
                    <ul class="nav-profile">
                    <?php
                    if ($thisclient && is_object($thisclient) && $thisclient->isValid()
                        && !$thisclient->isGuest()) {
                    ?>
                        <li class="profile dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <?php $ava = '';
                                    if ($cfg->isAvatarsEnabled())
                                        $ava = $thisclient->getAvatar();
                                    ?>
                                <?php if ($ava) { ?>
                                   <div class="img"><?php echo $ava; ?></div>
                                <?php } else { ?>
                                    <div class="img" style="background-image: url('<?php echo MODULAR_PATH; ?>assets/faces/8.jpg')"> </div>
                                <?php } ?>
                                <span class="name"><?php echo Format::htmlchars($thisclient->getName()); ?> </span>
                            </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="<?php echo ROOT_PATH; ?>profile.php">
                                        <i class="fa fa-user icon"></i><?php echo __('Profile'); ?></a>
                                    <a class="dropdown-item" href="<?php echo ROOT_PATH; ?>tickets.php">
                                        <i class="fa fa-bell icon"></i><?php echo sprintf(__('Tickets <b>(%d)</b>'), $thisclient->getNumTickets()); ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo $signout_url; ?>">
                                        <i class="fa fa-power-off icon"></i><?php echo __('Sign Out'); ?></a>
                                </div>
                        </li>
                    <?php
                    } elseif($nav) {
                        if ($cfg->getClientRegistrationMode() == 'public') { ?>
                            <?php echo '<li class="d-none d-md-block"><a>'.__('Guest User').'</a></li>'; ?><?php
                        }
                        if ($thisclient && $thisclient->isValid() && $thisclient->isGuest()) { ?>
                            <li class="header-block header-block-buttons"><a class="btn header-btn" href="<?php echo $signout_url; ?>"><i class="fa fa-sign-out"></i><span><?php echo __('Sign Out'); ?></span></a></li><?php
                        }
                        elseif ($cfg->getClientRegistrationMode() != 'disabled') { ?>
                            <li class="header-block header-block-buttons"><a class="btn header-btn" href="<?php echo $signin_url; ?>"><i class="fa fa-sign-in"></i><span><?php echo __('Sign In'); ?></span></a></li>
                        <?php
                        }
                    } ?>
                    <?php
                    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
                    && (count($all_langs) > 1)
                    ) {
                        $qs = array();
                        parse_str($_SERVER['QUERY_STRING'], $qs);?>
                        <li class="dropdown">
                            <a class="" data-toggle="dropdown">
                                <i class="fa fa-flag icon"></i>
                            </a>
                            <div class="dropdown-menu">

                        <?php 
                        foreach ($all_langs as $code=>$info) {
                            list($lang, $locale) = explode('_', $code);
                            $qs['lang'] = $code;
                        ?>
                            <a class="dropdown-item flag flag-<?php echo strtolower($info['flag'] ?: $locale ?: $lang); ?>"
                            href="?<?php echo http_build_query($qs);
                            ?>" title="<?php echo Internationalization::getLanguageDescription($code); ?>">&nbsp;</a>
                        <?php } ?>
                            </div> 
                        </li>
                        <?php 
                    } ?>
                    </ul>
                </div>
            </header>
            <aside class="sidebar">
                <div class="sidebar-container">
                    <div class="sidebar-header" href="<?php echo ROOT_PATH; ?>index.php">
                        <div class="brand">
                            <div class="logo">
                                <span class="l l1"></span>
                                <span class="l l2"></span>
                                <span class="l l3"></span>
                                <span class="l l4"></span>
                                <span class="l l5"></span>
                            </div> 
                            <?php echo $ost->getConfig()->getTitle(); ?>
                        </div>
                    </div>
                    <nav class="menu">
                        <?php
                        if($nav){ ?>
                        <ul class="sidebar-menu metismenu" id="sidebar-menu">
                            <?php
                            if($nav && ($navs=$nav->getNavLinks()) && is_array($navs)){
                                foreach($navs as $name =>$nav) {
                                    echo sprintf('<li class="%s"><a class="%s" href="%s"><i class="fa %s"></i>%s</a></li>%s',$nav['active']?'active':'',$name,(ROOT_PATH.$nav['href']),$nav['icon'],$nav['desc'],"\n");
                                }
                            } ?>
                        </ul>
                        <?php
                        } ?>
                    </nav>
                </div>
                <footer class="sidebar-footer">
                    <ul class="sidebar-menu metismenu" id="customize-menu">
                            <li>
                                <ul>
                                    <li class="customize">
                                        <div class="customize-item">
                                            <div class="row customize-header">
                                                <div class="col-4"> </div>
                                                <div class="col-4">
                                                    <label class="title">fixed</label>
                                                </div>
                                                <div class="col-4">
                                                    <label class="title">static</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="title">Sidebar:</label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed">
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="sidebarPosition" value="">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="title">Header:</label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="headerPosition" value="">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="title">Footer:</label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label>
                                                        <input class="radio" type="radio" name="footerPosition" value="">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customize-item">
                                            <ul class="customize-colors">
                                                <li>
                                                    <span class="color-item color-red" data-theme="red"></span>
                                                </li>
                                                <li>
                                                    <span class="color-item color-orange" data-theme="orange"></span>
                                                </li>
                                                <li>
                                                    <span class="color-item color-green active" data-theme=""></span>
                                                </li>
                                                <li>
                                                    <span class="color-item color-seagreen" data-theme="seagreen"></span>
                                                </li>
                                                <li>
                                                    <span class="color-item color-blue" data-theme="blue"></span>
                                                </li>
                                                <li>
                                                    <span class="color-item color-purple" data-theme="purple"></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <a href="">
                                    <i class="fa fa-cog"></i> Customize </a>
                            </li>
                        </ul>
                </footer>
            </aside>
            <div class="sidebar-overlay" id="sidebar-overlay"></div>
            <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
            <div class="mobile-menu-handle"></div>
            <article class="content">
                <?php if($errors['err']) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $errors['err']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php }elseif($msg) { ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php }elseif($warn) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $warn; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>

         
