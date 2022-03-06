<?php

namespace Neo\utils;

use pocketmine\Server;
use pocketmine\utils\Config;

trait TraitDataUtils {

    # 데이터 파일 경로
    private static function getPath() : string {
        return Server::getInstance()->getDataPath()."\\plugin_data\\Level\\data.json";
    }

    # 콘피그 핸들 값을 리턴
    private static function handleConfig() : Config{
        return new Config(self::getPath(), Config::JSON);
    }

    # 전체 데이터 설정 메소드
    public static function setData(array $data) : void {    
        file_put_contents(self::getPath(), json_encode($data));
    }

    # 전체 데이터 얻는 메소드
    public static function getData() : array {
        return (array) json_decode(file_get_contents(self::getPath()), true);
    }

}

?>