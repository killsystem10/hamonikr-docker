<?php
/**
 * FN.php
 * This file acts as the "backend controller" to your application. You can
 * get git clone source using this.
 * PHP error_reporting level may also be changed.
 *
 * @see http://hamonikr.org
 *
 * The MIT License (MIT)
 *
 * Copyright (c) <hamonikr@gmail.com> <HamoniKR.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

function Alert($msg){echo '<script>alert("'.$msg.'");</script>';}
function Loca($url){echo '<script language="javascript">document.location.href="'.$url.'";</script>';}
function Hist($go=false){$go=($go!=false)?$go:'-1';echo '<script>history.go("'.$go.'");</script>';}
function WinC($V=false){echo '<script>window.close();</script>';}
function SLASH($V,$E=false){
	if($E!=false){
//		$V=htmlspecialchars_decode($V);
//		$V=str_replace("||chr(13)", chr(13), $V);
//		$V=str_replace("||chr(10)", chr(10), $V);
		$V=str_replace("\'", "'", $V);
		$V=str_replace('&quot;', '"', $V);
		$V=str_replace(chr(92), "￦", $V);
//		$V=stripslashes($V);
	}else{
//		$V=addslashes($V);
		$V=str_replace(chr(92), "￦", $V);
		$V=str_replace('"','&quot;',$V);
		$V=str_replace("'", "\'", $V);
//		$V=str_replace(chr(10), "||chr(10)", $V);
//		$V=str_replace(chr(13), "||chr(13)", $V);
//		$V=htmlspecialchars($V);
	}
	return $V;
}
function Paging($N,$T,$L,$S,$B,$url2=''){$url=($url2=='')?'/images/common':$url2;$AllPage=ceil($T/$S);$Block=ceil($N/$B)*$B;$prev=($N<($B+1))?' ':'<a href="?'.$L.'&_N='.($Block-$B).'"><img style="vertical-align:top;" src="'.$url.'/sub04_list_box_before.gif" border="0" /></a>';$next=($AllPage<=$Block)?' ':'<a href="?'.$L.'&_N='.($Block+1).'"><img style="vertical-align:top;" src="'.$url.'/sub04_list_box_next.gif" border="0" /></a>';$fi=$Block-$B+1;$ei=($Block>$AllPage)?$AllPage:$Block;$HTML='<table border="0" cellpadding="0" cellspacing="0" cellspacing="0">';$HTML.='<tr>';$HTML.=(($N>$B)?'<td style="height:23px;"><a href="?'.$L.'&_N=1"><img style="vertical-align:top;" src="'.$url.'/sub04_list_box_ebefore.gif" border="0" /></td>':'');$HTML.='<td style="height:23px;">'.$prev.'</td>';for($i=$fi;$i<=$ei;$i++){if($N!=$i) $HTML.='<td align="center" width="22" background="'.$url.'/sub04_list_nobox_on.gif" style="padding:0px;cursor:pointer;font-size:10px;height:23px;" onclick="location.href=\'?'.$L.'&_N='.$i.'\';">'.$i.'</a></td>';else $HTML.='<td align="center" width="22" background="'.$url.'/sub04_list_nobox_off.gif" style="padding:0px;color:#ffffff;font-size:10px;height:23px;"><b>'.$i.'</b></td>';/*if($i<$ei) $HTML.=' ∥ ';*/}$HTML.='<td style="height:23px;">'.$next.'</td>';$HTML.=(($Block/$B<ceil($AllPage/$B))?'<td style="height:23px;"><a href="?'.$L.'&_N='.$AllPage.'"><img style="vertical-align:top;" src="'.$url.'/sub04_list_box_enext.gif" border="0" /></td>':'');$HTML.='</tr>';$HTML.='</table>';return $HTML;}
function Sele($S, $D, $V=false){$R = ($S==$D)?' selected':'';if($V!=false){return $R;}else{echo $R;}}
function RE($R, $C, $V=false) {$RR = str_replace(strtolower(URLDecode($C)), '<span style="color:#990000;font-weight:bold;background-color:yellow;">'.URLDecode($C).'</span>', URLDecode($R));if($V!=false){return $RR;}else{echo $RR;}}
function Order($ord, $asc, $N, $L, $V=false){$S = $_SERVER['PHP_SELF'];$H = '&nbsp;&nbsp;<a href="'.$S.'?'.$L.'&_Ord='.$ord.'&_Asc=asc">'.(($ord==$N&&$asc=='asc')?'▲':'△').'</a><a href="'.$S.'?'.$L.'&_Ord='.$ord.'&_Asc=desc">'.(($ord==$N&&$asc=='desc')?'▼':'▽').'</a>';if($V!=false) return $H;else echo $H;}
?>
