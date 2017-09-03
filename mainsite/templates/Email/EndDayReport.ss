<!doctype html>
<!--Quite a few clients strip your Doctype out, and some even apply their own. Many clients do honor your doctype and it can make things much easier if you can validate constantly against a Doctype.-->
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email template</title>
    <style type="text/css">
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        /* What it does: Forces Outlook.com to display emails full width. */
        .ExternalClass {
            width: 100%;
        }
        /* What is does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table, td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            max-width: 100% !important;
            height: auto !important;
            -ms-interpolation-mode: bicubic;
        }
        /* What it does: Overrides styles added when Yahoo's auto-senses a link. */
        .yshortcuts a {
            border-bottom: none !important;
        }
        /* What it does: Another work-around for iOS meddling in triggered links. */
        a[x-apple-data-detectors] {
            color: inherit !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body yahoo="yahoo">
    <table class="super-ancestor-table" width="100%" cellspacing="0" cellpadding="0" align="center">
        <tbody>
            <tr>
                <td class="main-container">
                    <table class="ancestor-table top-stripe" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                            <tr>
                                <td class="main-container">
                                    <!-- start -->
                                    <!--[if !mso]><!-- -->
                                    <!--<![endif]-->
                                    <table class="alpha-center-table maintain-table-on-mobile" width="600" cellpadding="0" cellspacing="0">
                                        <tr class="align-vertical-center">
                                            <td class="col-left is-8 has-text-left">
                                                <h2 class="title is-marginless">$Title</h2>
                                            </td>
                                            <td class="col-right is-4 has-text-right">
                                                <span style="font-size: 24px; font-weight: bold;">$Total</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- end -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="ancestor-table orange" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                            <tr>
                                <td class="main-container">
                                    <!-- start -->
                                    <table class="alpha-center-table" width="600" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="col-left has-text-left">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <!-- end -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="ancestor-table white" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                            <tr>
                                <td class="main-container">
                                    <!-- start -->
                                    <table class="alpha-center-table" width="600" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="has-text-left content">
                                                <% loop $Tasks %>
                                                    <h3>$Title</h3>
                                                    <% loop $List %>
                                                    <p><% if $JobNumber %><span class="task-badge">$JobNumber</span> <% end_if %> $Title, <strong class="is-black">$Duration</strong></p>
                                                    <% end_loop %>
                                                <% end_loop %>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- end -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="ancestor-table purple" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                            <tr>
                                <td class="main-container">
                                    <!-- start -->
                                    <table class="alpha-center-table closure" width="600" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="reported-by">Reported by $Member.FirstName $Member.Surname</td>
                                        </tr>
                                    </table>
                                    <!-- end -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </tbody>
</body>
</html>
