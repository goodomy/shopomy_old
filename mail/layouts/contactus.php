<?php
       use yii\helpers\Url;
?>
<table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif;border-collapse:collapse;width:100%!important;font-family:Helvetica,Arial,sans-serif;margin:0;padding:0" width="100%" bgcolor="#DFDFDF">
   <tbody>
    <tr>
     <td colspan="3">
       
       <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="table-layout:fixed">
              <tbody>
               <tr>
                <td align="center">
                 <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif;min-width:290px" width="600">
                  <tbody>
                   <tr>
                    <td style="font-family:Helvetica,Arial,sans-serif">
                     <table width="1" border="0" cellspacing="0" cellpadding="1">
                      <tbody>
                       <tr>
                        <td>
                         <div style="min-height:8px;font-size:8px;line-height:8px">
                          &nbsp;
                         </div></td>
                       </tr>
                      </tbody>
                     </table>
                     <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="100%" bgcolor="#DDDDDD">
                      <tbody>
                       <tr>
                        <td align="left" valign="middle" width="95" height="21"><a style="text-decoration:none;border:none;display:block;min-height:21px;width:100%" target="_blank">
                        <img src="<?=Url::to("@web/img/logo.png",true) ?>" width="30px" alt="<?=Yii::$app->params['appName']?>" style="border:none;text-decoration:none">
                        </a></td>
                       </tr>
                      </tbody>
                     </table>
                     <table width="1" border="0" cellspacing="0" cellpadding="1">
                      <tbody>
                       <tr>
                        <td>
                         <div style="min-height:8px;font-size:8px;line-height:8px">
                          &nbsp;
                         </div></td>
                       </tr>
                      </tbody>
                     </table>
                     <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="100%" bgcolor="#333333">
                      <tbody>
                       <tr>
                        <td width="20">
                         <table width="20" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:0px;font-size:0px;line-height:0px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                        <td width="100%">
                         <table width="560" cellspacing="0" cellpadding="1" border="0" style="table-layout:fixed">
                          <tbody>
                           <tr>
                            <td width="560">
                             <div style="min-height:12px;font-size:12px;line-height:12px;width:560px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                        <td width="20">
                         <table width="20" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:0px;font-size:0px;line-height:0px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                       </tr>
                      </tbody>
                     </table>
                     <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="100%" bgcolor="#FFFFFF">
                      <tbody>
                       <tr>
                        <td width="20">
                         <table width="20px" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:0px;font-size:0px;line-height:0px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                        <td style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:15px;line-height:18px" align="left">
                         <table width="1" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:20px;font-size:20px;line-height:20px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table>
                         <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="100%">
                          <tbody>
                           <tr>
                            <?php
                                   if(isset($subject) && $subject != '')
                                   {
                            ?>
                            <td style="font-family:Helvetica,Arial,sans-serif;color:#333333"> <div style="width: 80px;display: inline-block"><b>Subject</b></div> <div style="display: inline-block"><?=$subject?> </div></td>
                            <?php
                                   }
                            ?>
                           </tr>
                           <tr>
                            <td style="font-family:Helvetica,Arial,sans-serif;color:#333333"> <div style="width: 80px;display: inline-block;"><b>Message</b></div> <div style="display: inline-block"><?=$message?> </div></td>
                           </tr>
                           <tr>
                            <td style="border-bottom-color:#e5e5e5;border-bottom-width:1px;border-bottom-style:solid">
                             <table width="1" border="0" cellspacing="0" cellpadding="1">
                              <tbody>
                               <tr>
                                <td>
                                 <div style="min-height:20px;font-size:20px;line-height:20px">
                                  &nbsp;
                                 </div></td>
                               </tr>
                              </tbody>
                             </table></td>
                           </tr>
                          </tbody>
                         </table>
                         <table width="1" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:20px;font-size:20px;line-height:20px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                        <td width="20">
                         <table width="20px" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:0px;font-size:0px;line-height:0px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                       </tr>
                      </tbody>
                     </table></td>
                   </tr>
                  </tbody>
                 </table>
                 <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="600">
                  <tbody>
                   <tr>
                    <td align="left">
                     <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif" width="100%">
                      <tbody>
                       <tr>
                        <td align="left">
                         <table border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif;font-size:11px;font-family:Helvetica,Arial,sans-serif;color:#999999" width="100%">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:20px;font-size:20px;line-height:20px">
                              &nbsp;
                             </div>
                            </td>
                           </tr>
                           <tr>
                            <td>You are getting this email from <?=Yii::$app->params['appName']; ?>.</td>
                           </tr>
                           <tr>
                            <td>
                             <table width="1" border="0" cellspacing="0" cellpadding="1">
                              <tbody>
                               <tr>
                                <td>
                                 <div style="min-height:10px;font-size:10px;line-height:10px">
                                  &nbsp;
                                 </div></td>
                               </tr>
                              </tbody>
                             </table></td>
                           </tr>
                          </tbody>
                         </table></td>
                       </tr>
                       <tr>
                        <td>
                         <table width="1" border="0" cellspacing="0" cellpadding="1">
                          <tbody>
                           <tr>
                            <td>
                             <div style="min-height:20px;font-size:20px;line-height:20px">
                              &nbsp;
                             </div></td>
                           </tr>
                          </tbody>
                         </table></td>
                       </tr>
                      </tbody>
                     </table></td>
                   </tr>
                  </tbody>
                 </table></td>
               </tr>
              </tbody>
             </table>
     </td>
    </tr>
   </tbody>
</table>