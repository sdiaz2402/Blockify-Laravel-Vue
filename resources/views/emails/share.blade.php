<html>

    <head>

    </head>

    <body>
	<div style="background: #efefef;margin-top:10px; padding:10px" >

    <div ref="email" style="background-color:#efefef;" >
        <center>
		<table style="width: 580px;  background-color: #fff; margin-left:auto; margin-right:auto;" id="main">
            <tr>
                <td style="display:none !important;
           visibility:hidden;
           mso-hide:all;
           font-size:1px;
           color:#ffffff;
           line-height:1px;
           max-height:0px;
           max-width:0px;
           opacity:0;
           overflow:hidden;">
  My Stock News App<br/>All you need on a daily basis
</td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%;  background-color: #fff; margin-top:20px ">
                        <tr>
                            <td style="width: 40%;text-align:center">
                                <img style="width:50px; margin:auto" src="http://app.stocksnews.app/images/logo.png"  />
                            </td>
                            <td style="width: 60%">
                                <p style="font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 20px;"> My Stock News App</p>
                                <p style="font-family:Gilroy-light,Helvetica;text-align:center; font-size: 16px">All you need on a daily basis</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if($subtopic != "")
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>

                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 40px; text-transform: uppercase;">Ticker: {{ $subtopic }}</span> </td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>

                        </tr>
                    </table>
                </td>
            </tr>
            @endif
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>
                        <td style="color:#0015AC;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:left; font-size: 20px; text-transform: uppercase;">{{ $text }}</td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <p> {{ $content }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <p style="font-weight:bold;font-family:Gilroy-Bold,Helvetica;font-size: 16px; text-transform: uppercase;text-align:center"><a href="{{$link}}"> Read the full Story!</a></p>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>
                        <td style="color:#666666;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 20px; text-transform: uppercase;">Don't have an account?</td>
                        </tr>
                        <tr>
                            <td style="color:#000000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 20px; text-transform: uppercase;"><a href="https://app.stocksnews.app/register">Create an Account Now! Its Free!</a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding:10px 30px 0px 30px">

                </td>
            </tr>
            <tr>
                <td style="padding:50px 0px 20px 0px;text-align:center">
                    <footer id="footer"><div style="margin: 0; --text-opacity: 1; color: rgba(55, 65, 81, var(--text-opacity));text-align:center">
                            <img style="width:100px; margin:auto" src="http://app.stocksnews.app/images/logo.png"  />
                        </div><div><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="95%" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-left: auto; margin-right: auto; border-collapse: collapse;"><tbody><tr><td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="margin-top: 16px; margin-bottom: 16px;"></div></td></tr></tbody></table></div></footer>
                </td>
            </tr>

        </table>
        </center>
        </div>
	</div>
</body>
</html>


