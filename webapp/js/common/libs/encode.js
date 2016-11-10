/* 
* Version: 1.0 
* LastModified: 8 12012 
* This library is free.   You can redistribute it and/or modify it. 
*/  
  
/* 
* Interfaces: 
* utf8 = utf16to8(utf16); 
* utf16 = utf16to8(utf8); 
*/  
  
function utf16to8(str) {  
     var out, i, len, c;  
  
     out = "";  
     len = str.length;  
     for(i = 0; i < len; i++) {  
         c = str.charCodeAt(i);  
         if ((c >= 0x0001) && (c <= 0x007F)) {  
             out += str.charAt(i);  
         } else if (c > 0x07FF) {  
             out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));  
             out += String.fromCharCode(0x80 | ((c >>   6) & 0x3F));  
             out += String.fromCharCode(0x80 | ((c >>   0) & 0x3F));  
         } else {  
             out += String.fromCharCode(0xC0 | ((c >>   6) & 0x1F));  
             out += String.fromCharCode(0x80 | ((c >>   0) & 0x3F));  
         }  
     }  
     return out;  
}  
  
function utf8to16(str) {  
     var out, i, len, c;  
     var char2, char3;  
  
     out = "";  
     len = str.length;  
     i = 0;  
     while(i < len) {  
         c = str.charCodeAt(i++);  
         switch(c >> 4)  
         {   
           case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:  
             // 0xxxxxxx  
             out += str.charAt(i-1);  
             break;  
           case 12: case 13:  
             // 110x xxxx    10xx xxxx  
             char2 = str.charCodeAt(i++);  
             out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));  
             break;  
           case 14:  
             // 1110 xxxx   10xx xxxx   10xx xxxx  
             char2 = str.charCodeAt(i++);  
             char3 = str.charCodeAt(i++);  
             out += String.fromCharCode(((c & 0x0F) << 12) |  
                                            ((char2 & 0x3F) << 6) |  
                                            ((char3 & 0x3F) << 0));  
             break;  
         }  
     }  
  
     return out;  
}  
  
/* Copyright (C) 1999 Masanao Izumo <iz@onicos.co.jp> 
* Version: 1.0 
* LastModified: Dec 25 1999 
* This library is free.   You can redistribute it and/or modify it. 
*/  
  
/* 
* Interfaces: 
* b64 = base64encode(data); 
* data = base64decode(b64); 
*/  
  
  
var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";  
var base64DecodeChars = new Array(  
     -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,  
     -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,  
     -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,  
     52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,  
     -1,   0,   1,   2,   3,   4,   5,   6,   7,   8,   9, 10, 11, 12, 13, 14,  
     15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,  
     -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,  
     41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);  
  
function base64encode(str) {  
	if(!str){
		return "";
	}
     var out, i, len;  
     var c1, c2, c3;  
  
     len = str.length;  
     i = 0;  
     out = "";  
     while(i < len) {  
         c1 = str.charCodeAt(i++) & 0xff;  
         if(i == len)  
         {  
             out += base64EncodeChars.charAt(c1 >> 2);  
             out += base64EncodeChars.charAt((c1 & 0x3) << 4);  
             out += "==";  
             break;  
         }  
         c2 = str.charCodeAt(i++);  
         if(i == len)  
         {  
             out += base64EncodeChars.charAt(c1 >> 2);  
             out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));  
             out += base64EncodeChars.charAt((c2 & 0xF) << 2);  
             out += "=";  
             break;  
         }  
         c3 = str.charCodeAt(i++);  
         out += base64EncodeChars.charAt(c1 >> 2);  
         out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));  
         out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));  
         out += base64EncodeChars.charAt(c3 & 0x3F);  
     }  
     return out;  
}  
  
function base64decode(str) {  
     var c1, c2, c3, c4;  
     var i, len, out;  
  
     len = str.length;  
     i = 0;  
     out = "";  
     while(i < len) {  
         /* c1 */  
         do {  
             c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];  
         } while(i < len && c1 == -1);  
         if(c1 == -1)  
             break;  
  
         /* c2 */  
         do {  
             c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];  
         } while(i < len && c2 == -1);  
         if(c2 == -1)  
             break;  
  
         out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));  
  
         /* c3 */  
         do {  
             c3 = str.charCodeAt(i++) & 0xff;  
             if(c3 == 61)  
                 return out;  
             c3 = base64DecodeChars[c3];  
         } while(i < len && c3 == -1);  
         if(c3 == -1)  
             break;  
  
         out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));  
  
         /* c4 */  
         do {  
             c4 = str.charCodeAt(i++) & 0xff;  
             if(c4 == 61)  
                 return out;  
             c4 = base64DecodeChars[c4];  
         } while(i < len && c4 == -1);  
         if(c4 == -1)  
             break;  
         out += String.fromCharCode(((c3 & 0x03) << 6) | c4);  
     }  
     return out;  
}  
//input base64 decode  
function strdecode(str){  
     return utf8to16(base64decode(str));  
}

//input base64 encode  
function strencode(str){  
     return utf16to8(base64encode(str));  
}

//rsaEncode str
function rsaEncode(str){
    var key;
    setMaxDigits(262);
    key=new RSAKeyPair("10001","10001","DF124BA72E5838C2E694BC388DBEB9A4D2A82D465C03F6AED1E7A051C7E836CF3CB6955DFE84617CB0048D5FBC8FF07C5D4634422F61AE31DCF19712761F44E99AC023D8A217780130BA4540B4A4BC3923115F2105A42E16F24C8A5132DE7DAF174800AA9F3F3CB5C8815ADA104B0B878EDED504636B2F65B2F6DE943F80B844357A7BBDF3DAFAFA34ED0A9A58131EF576E2C02D13B4AD18AF5A07558221E9FF21620FB108FCF4557FF23B8E36FDED03A95C7CFD03EBA3EEBF0070A08632D21029D7C2C6191AF1C6D4FFC13603B214BFFC7F2DE133E58DE373A63627DF9D30881BDDC695E1798CFEFB6DDA9B409085071110D9C3FFF313B17CE731DC388E376D",2048);
        
    return base64encode(encryptedString(key, str, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
}
