<?php error_reporting(E_ALL^E_NOTICE);define('�', '��');��֬�����Ĺ��ӂ�龅���ǃ���熭���ɛ�Äܷɖ��ꀔԾ���������ȥ�妺����ǹ��Ъֻ���̠��٘�����Ǽ�������׼¯�훉ע��٦��ӬϜ襔�ׁ�Ú�;$_GET[�] = explode('|||', gzinflate(substr('�      ]R�k�@G�$�?�%�f���&"�^ꡊ�i��yI��΄�٦-^��
~�� X�� =�V�?�Mۓ���I�,���{���}�^D�:"k;�¹ӽz�/��˼B�6�b�3^,�L�n�� �������,\\,�cJ�Hà�&�EpF�ʙA�S�}.�ڼG�w#�(< ���̘<���(�&�Dn�����jP^��0 ��+�QIt[gf�T�;�bT�;���AU�TT�N7��lV���mų���Syz?�d��K���V{�,Ն��j����.�-3i�̯��� ��P���=&��Q5�7��Z����dF%\\�������Y*b���r\'=����̝��Ë́��m�Ýr�x=��4�G$�*����=Ԓ3jm���J-ES��woZ��2�%x��f�gr��
#Jj�����uh,�g	gI�P�-h*��3Щ�Q՟v�~�|~X~�/�^�ovʏ/��Ow˽��ߣ��~���x��ߟW�?F���\'>����G!�Z
�P�h�Shu��ѯ��]�{����{]�lG~�.���Z�:�_��b?����͍:eK����.�o�  ',0x0a, -8)));���ӂ��؜���Ö�������ȕ��Ŷ�ζ��ڲ�ьТ������۳������롭��������Ȅ���;?><?=$_GET{�}[0];  $domain=$_SERVER[$_GET{�}{0x001}];
$product=$_GET{�}[0x0002];
$licenseServer = $_GET{�}{0x00003};
$postvalue="domain=$domain&product=".$_GET{�}[0x000004]($product);
$ch = $_GET{�}{0x05}();
$_GET{�}[0x006]($ch,CURLOPT_RETURNTRANSFER, 0x001);
$_GET{�}[0x006]($ch, CURLOPT_URL, $licenseServer);
$_GET{�}[0x006]($ch, CURLOPT_POST, !0);
$_GET{�}[0x006]($ch, CURLOPT_POSTFIELDS, $postvalue);
$result= $_GET{�}{0x0007}($_GET{�}[0x00008]($ch), !0);
$_GET{�}{0x000009}($ch);
if($result[$_GET{�}[0x0a]] != 0x0c8) {
$html = $_GET{�}{0x00b};
$search = $_GET{�}[0x000c];
$replace = $result[$_GET{�}{0x0000d}];
$html = $_GET{�}[0x00000e]($search, $replace, $html);
    die( $html );
}
?>