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




			<tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>
                        <td style="color:#0015AC;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 20px; text-transform: uppercase;">ALERT</td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 50px; text-transform: uppercase;">{{$ticker}} - <span style="color:#<?php ($change > 0) ? 'F00' : '028140' ?>"> {{ $change }}% </span> </td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>
                        </tr>
                        <tr>
                        <td style="color:#666;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 30px; text-transform: uppercase;">Unread News: {{ $unread }} </td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 10px 0px 10px">
                    <table style="width: 100%;  background-color: #fff;margin-top:20px ">
                        <tr>
                        <td style="color:#000000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 20px; text-transform: uppercase;">Last 5 News</td>
                        <td style="color:#000;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:right; font-size: 12px"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 30px 0px 30px">
                    <table style="width: 100%;  background-color: #fff; ">
                        <tr>
                            <th style="color:#A2A2A2;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 16px; width:70%">Headline </th>
                            <th style="color:#A2A2A2;font-weight:bold;font-family:Gilroy-Bold,Helvetica; text-align:center; font-size: 16px; width:10%"></th>
                        </tr>
                        @php
                            $index = 0;
                        @endphp
                        @foreach($last_5 as $object)

                        <tr style="background-color:{{ ($index % 2 == 0 ? '#fffff' : '#EFEFEF') }}">
                            <td style="padding:4px;font-family:Gilroy-light,Helvetica; text-align:left; font-size: 16px;">
                                <span style="font-size:20px"> {{ $object->created_at->format('M d - H:i') }}</span><br/>
                                {{ $object->text }} - {{ strtoupper($object->author) }}</td>
                            <td style="padding:2px;font-family:Gilroy-light,Helvetica; text-align:center; font-size: 16px"><a class="text-blue-600 font-bold" target="_blank" href="{{ url("/app/news/".$object->link) }}">Read</router-link></td>
                        </tr>
                        <?php $index++ ?>
                        @endforeach

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


