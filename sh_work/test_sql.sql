#!/bin/bash
dbname=18av_xfl 

mysql ${dbname} <<EOF


/* 刪除敏感字 */


DELETE FROM mac_vod WHERE vod_name LIKE '%高校3年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%高三%';
DELETE FROM mac_vod WHERE vod_name LIKE '%養狗%';
DELETE FROM mac_vod WHERE vod_name LIKE '%非倫理的%';
DELETE FROM mac_vod WHERE vod_name LIKE '%雌犬%';
DELETE FROM mac_vod WHERE vod_name LIKE '%近親者%';
DELETE FROM mac_vod WHERE vod_name LIKE '%近親相姦%';
DELETE FROM mac_vod WHERE vod_name LIKE '%近親%';
DELETE FROM mac_vod WHERE vod_name LIKE '%近亲相奸%';
DELETE FROM mac_vod WHERE vod_name LIKE '%近亲%';
DELETE FROM mac_vod WHERE vod_name LIKE '%轮奸%';
DELETE FROM mac_vod WHERE vod_name LIKE '%輪姦%';
DELETE FROM mac_vod WHERE vod_name LIKE '%親父%';
DELETE FROM mac_vod WHERE vod_name LIKE '%親子%';
DELETE FROM mac_vod WHERE vod_name LIKE '%虐待%';
DELETE FROM mac_vod WHERE vod_name LIKE '%稚嫩%';
DELETE FROM mac_vod WHERE vod_name LIKE '%祖父母%';
DELETE FROM mac_vod WHERE vod_name LIKE '%祖孫%';
DELETE FROM mac_vod WHERE vod_name LIKE '%祖孙%';
DELETE FROM mac_vod WHERE vod_name LIKE '%生物学上の父親%';
DELETE FROM mac_vod WHERE vod_name LIKE '%父子%';
DELETE FROM mac_vod WHERE vod_name LIKE '%父女%';
DELETE FROM mac_vod WHERE vod_name LIKE '%母犬%';
DELETE FROM mac_vod WHERE vod_name LIKE '%母子%';
DELETE FROM mac_vod WHERE vod_name LIKE '%未滿18%';
DELETE FROM mac_vod WHERE vod_name LIKE '%未满18%';
DELETE FROM mac_vod WHERE vod_name LIKE '%未成年者%';
DELETE FROM mac_vod WHERE vod_name LIKE '%未成年%';
DELETE FROM mac_vod WHERE vod_name LIKE '%幼%';
DELETE FROM mac_vod WHERE vod_name LIKE '%小學校%';
DELETE FROM mac_vod WHERE vod_name LIKE '%小学校%';
DELETE FROM mac_vod WHERE vod_name LIKE '%姐弟%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國三%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國二%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國一%';
DELETE FROM mac_vod WHERE vod_name LIKE '%国三%';
DELETE FROM mac_vod WHERE vod_name LIKE '%国二%';
DELETE FROM mac_vod WHERE vod_name LIKE '%国一%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國中生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國中學生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國小%';
DELETE FROM mac_vod WHERE vod_name LIKE '%國中%';
DELETE FROM mac_vod WHERE vod_name LIKE '%国小%';
DELETE FROM mac_vod WHERE vod_name LIKE '%国中%';
DELETE FROM mac_vod WHERE vod_name LIKE '%動物%';
DELETE FROM mac_vod WHERE vod_name LIKE '%动物%';
DELETE FROM mac_vod WHERE vod_name LIKE '%初中生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%初三%';
DELETE FROM mac_vod WHERE vod_name LIKE '%初二%';
DELETE FROM mac_vod WHERE vod_name LIKE '%初一%';
DELETE FROM mac_vod WHERE vod_name LIKE '%养狗%';
DELETE FROM mac_vod WHERE vod_name LIKE '%兄弟姊妹%';
DELETE FROM mac_vod WHERE vod_name LIKE '%兄弟姉妹%';
DELETE FROM mac_vod WHERE vod_name LIKE '%兄妹%';
DELETE FROM mac_vod WHERE vod_name LIKE '%亲子%';
DELETE FROM mac_vod WHERE vod_name LIKE '%亂倫%';
DELETE FROM mac_vod WHERE vod_name LIKE '%乱伦%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中学生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中學3年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中學2年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中學1年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中学3年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中学2年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%中学1年生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%18歳未満%';
DELETE FROM mac_vod WHERE vod_name LIKE '%18歳以下%';
DELETE FROM mac_vod WHERE vod_name LIKE '%18岁未満%';
DELETE FROM mac_vod WHERE vod_name LIKE '%18岁以下%';
DELETE FROM mac_vod WHERE vod_name LIKE '%16岁未満%';
DELETE FROM mac_vod WHERE vod_name LIKE '%16岁以下%';
DELETE FROM mac_vod WHERE vod_name LIKE '%小学生%';
DELETE FROM mac_vod WHERE vod_name LIKE '%小女孩%';
DELETE FROM mac_vod WHERE vod_name LIKE '%幼子%';

SELECT count(vod_name) FROM  mac_vod WHERE vod_name REGEXP '3岁|4岁|5岁|6岁|7岁|8岁|9岁|10岁|11岁|12岁|13岁|14岁|15岁|16岁|17岁|幼女|幼儿|幼童' AND vod_name not REGEXP '[2-9]3岁|[2-9]4岁|[2-9]5岁|[2-9]6岁|[2-9]7岁|[2-9]8岁|[2-9]9岁|[2-9]0岁|19|18|26|28|38|37|27|47|48|50|26|33|29|36';

DELETE FROM mac_vod WHERE vod_name REGEXP '3岁|4岁|5岁|6岁|7岁|8岁|9岁|10岁|11岁|12岁|13岁|14岁|15岁|16岁|17岁|幼女|幼儿|幼童' AND vod_name not REGEXP '[2-9]3岁|[2-9]4岁|[2-9]5岁|[2-9]6岁|[2-9]7岁|[2-9]8岁|[2-9]9岁|[2-9]0岁|19|18|26|28|38|37|27|47|48|50|26|33|29|36';

SELECT count(vod_name) FROM  mac_vod WHERE vod_name REGEXP '3岁|4岁|5岁|6岁|7岁|8岁|9岁|10岁|11岁|12岁|13岁|14岁|15岁|16岁|17岁|幼女|幼儿|幼童' AND vod_name not REGEXP '[2-9]3岁|[2-9]4岁|[2-9]5岁|[2-9]6岁|[2-9]7岁|[2-9]8岁|[2-9]9岁|[2-9]0岁|19|18|26|28|38|37|27|47|48|50|26|33|29|36';

/* 影片分类 */

-- 传媒
UPDATE mac_vod SET type_id_1=200,type_id=203 WHERE (vod_name LIKE '%葫芦影业%')  ;
UPDATE mac_vod SET type_id_1=200,type_id=203 WHERE (vod_name LIKE '%葫蘆%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=204 WHERE (vod_name LIKE '%猫爪%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=204 WHERE (vod_name LIKE '%貓爪%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=205 WHERE (vod_name LIKE '%天美%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=206 WHERE (vod_name LIKE '%果冻%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=206 WHERE (vod_name LIKE '%果凍%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=207 WHERE (vod_name LIKE '%91制片%') ;
UPDATE mac_vod SET type_id_1=200,type_id=207 WHERE (vod_name LIKE '%91CM%') ;


UPDATE mac_vod SET type_id_1=200,type_id=208 WHERE (vod_name LIKE '%蜜桃%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=209 WHERE (vod_name LIKE '%精东%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=209 WHERE (vod_name LIKE '%精東%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=210 WHERE (vod_name LIKE '%皇家%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=211 WHERE (vod_name LIKE '%SWAG%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=212 WHERE (vod_name LIKE '%JVID%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=213 WHERE (vod_name LIKE '%逼哩%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=214 WHERE (vod_name LIKE '%杏吧%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=215 WHERE (vod_name LIKE '%兔子%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=216 WHERE (vod_name LIKE '%PsychoPornTW%')   ;

UPDATE mac_vod SET type_id_1=200,type_id=217 WHERE (vod_name LIKE '%MINI%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=218 WHERE (vod_name LIKE '%微啪%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=218 WHERE (vod_name LIKE '%陌丽%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=218 WHERE (vod_name LIKE '%陌麗%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=219 WHERE (vod_name LIKE '%大象%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=220 WHERE (vod_name LIKE '%乌鸦%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=220 WHERE (vod_name LIKE '%烏鴉%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=221 WHERE (vod_name LIKE '%乐播%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=221 WHERE (vod_name LIKE '%樂播%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=222 WHERE (vod_name LIKE '%糖心%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=223 WHERE (vod_name LIKE '%星空%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=224 WHERE (vod_name LIKE '%爱妃%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=224 WHERE (vod_name LIKE '%愛妃%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=225 WHERE (vod_name LIKE '%肉肉%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=226 WHERE (vod_name LIKE '%鲸鱼%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=226 WHERE (vod_name LIKE '%鯨魚%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=227 WHERE (vod_name LIKE '%91传媒%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=227 WHERE (vod_name LIKE '%91傳媒%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=228 WHERE (vod_name LIKE '%扣扣%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=229 WHERE (vod_name LIKE '%开心鬼%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=229 WHERE (vod_name LIKE '%開心鬼%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=230 WHERE (vod_name LIKE '%渡边%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=230 WHERE (vod_name LIKE '%渡邊%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=231 WHERE (vod_name LIKE '%换妻%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=235 WHERE (vod_name LIKE '%桃视频%') ;
UPDATE mac_vod SET type_id_1=200,type_id=232 WHERE (vod_name LIKE '%涩会%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=232 WHERE (vod_name LIKE '%澀會%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=233 WHERE (vod_name LIKE '%胖子%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=234 WHERE (vod_name LIKE '%SA国际%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=234 WHERE (vod_name LIKE '%SA國際%')   ;
UPDATE mac_vod SET type_id_1=200,type_id=202 WHERE (vod_name LIKE '%麻豆合集%' OR vod_name LIKE '%麻豆传媒%' OR vod_name LIKE '%麻豆%' OR vod_name LIKE '%传媒映画%' OR vod_name LIKE '%麻豆原创%');



-- 探花
UPDATE mac_vod SET type_id_1=500,type_id=501 WHERE (vod_name LIKE '%91沈先生%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=501 WHERE (vod_name LIKE '%91沈先森%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=501 WHERE (vod_name LIKE '%沈先生%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=503 WHERE (vod_name LIKE '%千人斩%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=503 WHERE (vod_name LIKE '%千人斬%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=503 WHERE (vod_name LIKE '%千人展%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=502 WHERE (vod_name LIKE '%文轩%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=502 WHERE (vod_name LIKE '%文軒%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=504 WHERE (vod_name LIKE '%太子%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=505 WHERE (vod_name LIKE '%屌哥全国%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=505 WHERE (vod_name LIKE '%屌哥全國%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=506 WHERE (vod_name LIKE '%鸭哥%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=506 WHERE (vod_name LIKE '%鴨哥%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=507 WHERE (vod_name LIKE '%9总全国%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=507 WHERE (vod_name LIKE '%9總全國%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=508 WHERE (vod_name LIKE '%小天探花%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=509 WHERE (vod_name LIKE '%李寻欢%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=509 WHERE (vod_name LIKE '%李尋歡%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=510 WHERE (vod_name LIKE '%小陈头星选%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=510 WHERE (vod_name LIKE '%小陳頭星選%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=512 WHERE (vod_name LIKE '%主播探花%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=513 WHERE (vod_name LIKE '%酒店%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=514 WHERE (vod_name LIKE '%小宝寻花%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=514 WHERE (vod_name LIKE '%小宝寻花%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=515 WHERE (vod_name LIKE '%午夜寻花%')  ;
UPDATE mac_vod SET type_id_1=500,type_id=516 WHERE (vod_name LIKE '%91大神%')    ;
UPDATE mac_vod SET type_id_1=500,type_id=516 WHERE vod_name  LIKE '%91%猫先生%'  ;
UPDATE mac_vod SET type_id_1=500,type_id=511 WHERE (vod_name LIKE '%主播探花%')  ;




-- 番号
UPDATE mac_vod SET type_id=319 WHERE vod_name LIKE '%GANA%' ;
UPDATE mac_vod SET type_id=301 WHERE vod_name LIKE '%200GANA%';
UPDATE mac_vod SET type_id=318 WHERE vod_name LIKE '%DCV%'    ;
UPDATE mac_vod SET type_id=307 WHERE vod_name LIKE '%277DCV%' ;
UPDATE mac_vod SET type_id=305 WHERE vod_name LIKE '%259LUXU%';
UPDATE mac_vod SET type_id=306 WHERE vod_name LIKE '%261ARA%' ;
UPDATE mac_vod SET type_id=302 WHERE vod_name LIKE '%300MIUM%';
UPDATE mac_vod SET type_id=308 WHERE vod_name LIKE '%300MAAN%';
UPDATE mac_vod SET type_id=309 WHERE vod_name LIKE '%300NTK%' ;
UPDATE mac_vod SET type_id=311 WHERE vod_name LIKE '%328HMDN%';
UPDATE mac_vod SET type_id=312 WHERE vod_name LIKE '%332NAMA%';
UPDATE mac_vod SET type_id=313 WHERE vod_name LIKE '%336KNB%' ;
UPDATE mac_vod SET type_id=314 WHERE vod_name LIKE '%348NTR%' ;
UPDATE mac_vod SET type_id=315 WHERE vod_name LIKE '%390JAC%' ;
UPDATE mac_vod SET type_id=317 WHERE vod_name LIKE '%428SUKE%';
UPDATE mac_vod SET type_id=329 WHERE vod_name LIKE '%AARM%'   ;
UPDATE mac_vod SET type_id=328 WHERE vod_name LIKE '%ADN%'    ;
UPDATE mac_vod SET type_id=333 WHERE vod_name LIKE '%ATID%'   ;
UPDATE mac_vod SET type_id=340 WHERE vod_name LIKE '%DFDM%'   ;
UPDATE mac_vod SET type_id=342 WHERE vod_name LIKE '%DLDSS%'  ;
UPDATE mac_vod SET type_id=345 WHERE vod_name LIKE '%DVAJ%'   ;
UPDATE mac_vod SET type_id=344 WHERE vod_name LIKE '%EBOD%'   ;
UPDATE mac_vod SET type_id=335 WHERE vod_name LIKE '%EYAN%' ;
UPDATE mac_vod SET type_id=304 WHERE vod_name LIKE '%SSNI%' ;
UPDATE mac_vod SET type_id=341 WHERE vod_name LIKE '%FSDSS%';
UPDATE mac_vod SET type_id=320 WHERE vod_name LIKE '%IPX%'  ;
UPDATE mac_vod SET type_id=346 WHERE vod_name LIKE '%JUTA%' ;
UPDATE mac_vod SET type_id=330 WHERE vod_name LIKE '%LULU%' ;
UPDATE mac_vod SET type_id=338 WHERE vod_name LIKE '%MIAA%' ;
UPDATE mac_vod SET type_id=324 WHERE vod_name LIKE '%MIDE%' ;
UPDATE mac_vod SET type_id=316 WHERE vod_name LIKE '%MIFD%' ;
UPDATE mac_vod SET type_id=331 WHERE vod_name LIKE '%MIMK%' ;
UPDATE mac_vod SET type_id=336 WHERE vod_name LIKE '%MRSS%' ;
UPDATE mac_vod SET type_id=326 WHERE vod_name LIKE '%MUDR%' ;
UPDATE mac_vod SET type_id=337 WHERE vod_name LIKE '%NNPJ%' ;
UPDATE mac_vod SET type_id=349 WHERE vod_name LIKE '%OKSN%' ;
UPDATE mac_vod SET type_id=323 WHERE vod_name LIKE '%PPB%'  ;
UPDATE mac_vod SET type_id=332 WHERE vod_name LIKE '%PPPD%' ;
UPDATE mac_vod SET type_id=321 WHERE vod_name LIKE '%PPT%'  ;
UPDATE mac_vod SET type_id=327 WHERE vod_name LIKE '%RBD%'  ;
UPDATE mac_vod SET type_id=347 WHERE vod_name LIKE '%REAL%' ;
UPDATE mac_vod SET type_id=303 WHERE vod_name LIKE '%SIRO%' ;
UPDATE mac_vod SET type_id=322 WHERE vod_name LIKE '%SIV%'  ;
UPDATE mac_vod SET type_id=343 WHERE vod_name LIKE '%SQTE%' ;
UPDATE mac_vod SET type_id=339 WHERE vod_name LIKE '%SSIS%' ;
UPDATE mac_vod SET type_id=334 WHERE vod_name LIKE '%STARS%';
UPDATE mac_vod SET type_id=325 WHERE vod_name LIKE '%ULT%'  ;
UPDATE mac_vod SET type_id=310 WHERE vod_name LIKE '%WANZ%' ;
UPDATE mac_vod SET type_id=348 WHERE vod_name LIKE '%XRW%'  ;
UPDATE mac_vod SET type_id=350 WHERE vod_name LIKE '%SGA%'  ;


-- 女优
UPDATE mac_vod SET type_id_1=400,type_id=407 WHERE (vod_name LIKE '%水卜櫻%' OR vod_name LIKE '%水卜さくら%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=408 WHERE (vod_name LIKE '%紗倉真菜%' OR vod_name LIKE '%紗倉まな%' OR vod_name LIKE '%纱仓真菜')  ;
UPDATE mac_vod SET type_id_1=400,type_id=410 WHERE (vod_name LIKE '%安齋拉拉%' OR vod_name LIKE '%安齋らら%' OR vod_name LIKE '%安斋拉拉%' OR vod_name LIKE '%安斋 らら%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=413 WHERE (vod_name LIKE '%櫻空桃%' OR vod_name LIKE '%桜空もも%' OR vod_name LIKE '%さくら もも%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=414 WHERE (vod_name LIKE '%Miru%' OR vod_name LIKE '%坂道みる%' OR vod_name LIKE '%坂道美琉%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=416 WHERE (vod_name LIKE '%山岸逢花%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=417 WHERE (vod_name LIKE '%七澤米亞%' OR vod_name LIKE '%七沢みあ%' OR vod_name LIKE '%七泽米亚%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=404 WHERE (vod_name LIKE '%河北彩花%' OR vod_name LIKE '%かわきた さいか%' OR vod_name LIKE '%河北 彩花%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=401 WHERE (vod_name LIKE '%夢乃愛華%' OR vod_name LIKE '%夢乃あいか%' OR vod_name LIKE '%梦乃爱华%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=419 WHERE (vod_name LIKE '%木下日葵%' OR vod_name LIKE '%木下ひまり%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=420 WHERE (vod_name LIKE '%二階堂夢%' OR vod_name LIKE '%本鄉愛%' OR vod_name LIKE '%二阶堂梦%' OR vod_name LIKE '%二階堂 夢%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=421 WHERE (vod_name LIKE '%田中宁宁%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=422 WHERE (vod_name LIKE '%藤森里穂%' OR vod_name LIKE '%田由美%' OR vod_name LIKE '%はるかみらい%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=423 WHERE (vod_name LIKE '%稻场流花%' OR vod_name LIKE '%稲場るか%' OR vod_name LIKE '%稻場流花%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=425 WHERE (vod_name LIKE '%有栖花绯%' OR vod_name LIKE '%有栖花あか%' OR vod_name LIKE '%有栖花绯%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=426 WHERE (vod_name LIKE '%沙月芽衣%' OR vod_name LIKE '%沙月とわ%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=427 WHERE (vod_name LIKE '%東條夏%' OR vod_name LIKE '%東條なつ%' OR vod_name LIKE '%东条夏%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=428 WHERE (vod_name LIKE '%北野望%' OR vod_name LIKE '%北野のぞみ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=430 WHERE (vod_name LIKE '%天音真比奈%' OR vod_name LIKE '%天音まひな%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=431 WHERE (vod_name LIKE '%早野詩%' OR vod_name LIKE '%はやのうた%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=433 WHERE (vod_name LIKE '%神宮寺奈緒%' OR vod_name LIKE '%神宮寺ナオ%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=434 WHERE (vod_name LIKE '%逢見梨花%' OR vod_name LIKE '%逢见梨花%' OR vod_name LIKE '%逢見 リカ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=435 WHERE (vod_name LIKE '%藍芽美月%' OR vod_name LIKE '%蓝芽美月%' OR vod_name LIKE '%藍芽みずき%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=436 WHERE (vod_name LIKE '%鈴木心春%' OR vod_name LIKE '%铃木心春%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=438 WHERE (vod_name LIKE '%架乃由羅%' OR vod_name LIKE '%架乃由罗%' OR vod_name LIKE '%架乃ゆら%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=440 WHERE (vod_name LIKE '%三宮椿%' OR vod_name LIKE '%三宮つばき%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=441 WHERE (vod_name LIKE '%川上奈奈美%' OR vod_name LIKE '%川上奈々美%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=442 WHERE (vod_name LIKE '%湊莉久%' OR vod_name LIKE '%凑莉久%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=443 WHERE (vod_name LIKE '%小島南%' OR vod_name LIKE '%小岛南%' OR vod_name LIKE '%小島みなみ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=444 WHERE (vod_name LIKE '%平手真菜%' OR vod_name LIKE '%有季なお%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=445 WHERE (vod_name LIKE '%新川愛七%' OR vod_name LIKE '%新川爱七%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=446 WHERE (vod_name LIKE '%鷲尾芽衣%' OR vod_name LIKE '%鹫尾芽衣%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=429 WHERE (vod_name LIKE '%明里紬%' OR vod_name LIKE '%明里つむぎ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=424 WHERE (vod_name LIKE '%久留木玲%' OR vod_name LIKE '%久留木 玲%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=437 WHERE (vod_name LIKE '%有坂深雪%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=432 WHERE (vod_name LIKE '%篠田優%' OR vod_name LIKE '%筱田优%' OR vod_name LIKE '%篠田ゆう%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=415 WHERE (vod_name LIKE '%羽咲美晴%' OR vod_name LIKE '%羽咲みはる%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=418 WHERE (vod_name LIKE '%松本一香%' OR vod_name LIKE '%松本いちか%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=409 WHERE (vod_name LIKE '%桃乃木香奈%' OR vod_name LIKE '%桃乃木かな%' OR vod_name LIKE '%松嶋真麻%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=411 WHERE (vod_name LIKE '%天使萌%' OR vod_name LIKE '%天使もえ%' OR vod_name LIKE '%あまつか もえ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=412 WHERE (vod_name LIKE '%相澤南%' OR vod_name LIKE '%相沢みなみ%' OR vod_name LIKE '%相泽南%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=406 WHERE (vod_name LIKE '%葵司%' OR vod_name LIKE '%葵つかさ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=405 WHERE (vod_name LIKE '%高橋聖子%' OR vod_name LIKE '%高橋 しょう子%' OR vod_name LIKE '%高桥圣子%' )  ;
UPDATE mac_vod SET type_id_1=400,type_id=403 WHERE (vod_name LIKE '%三上悠亚%' OR vod_name LIKE '%三上悠亞%' OR vod_name LIKE '%みかみ ゆあ%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=439 WHERE (vod_name LIKE '%美之嶋惠理%' OR vod_name LIKE '%美之嶋惠理%' OR vod_name LIKE '%美ノ嶋めぐり%')  ;
UPDATE mac_vod SET type_id_1=400,type_id=402 WHERE (vod_name LIKE '%波多野結衣%')  ;

/* 刪除表格中無用的空洞資料 */
optimize table mac_vod   ;


EOF
