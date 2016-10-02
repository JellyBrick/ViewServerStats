<?PHP

/*
 * The plugin that show you to use your 'Server Stats' in PocketMine-MP.
 * Copyright (C) 2016 JellyBrick_
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

namespace VSS;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Utils;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\Server;

class VSS extends PluginBase implements Listener {
 private $motd;
 private $players;
 private $ip;
	public function onEnable() {
       $this->getLogger()->info ( Color::BLUE . "VSS 플러그인을 활성화 합니다!" );
       $this->ip=Utils::getIp();
       $this->players=0;
       $motd = $this->getServer()->getConfigString ("motd","Minecraft: PE Server");
       $this->motd = $motd;
       $this->get_content("http://stat.mcpek.xyz/serverinfoup.php?motd=".$motd."&players=0&serverip=".$this->ip);

	public function playerJoin(PlayerJoinEvent $ev) {
	      $this->players++;	
	      $this->get_content("http://stat.mcpek.xyz/serverinfoup.php?motd=".$this->motd."&players=".$this->players."&serverip=".$this->ip);
	}
	
	public function playerQuit(PlayerQuitEvent $ev) {
	      $this->players--;
	      $this->get_content("http://stat.mcpek.xyz/serverinfoup.php?motd=".$this->motd."&players=".$this->players."&serverip=".$this->ip);
 }
	
	public function get_content($url) {
       $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)';
       $curlsession = curl_init ();
       curl_setopt ($curlsession, CURLOPT_URL, $url);
       curl_setopt ($curlsession, CURLOPT_HEADER, 0);
       curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($curlsession, CURLOPT_POST, 0);
       curl_setopt ($curlsession, CURLOPT_USERAGENT, $agent);
       curl_setopt ($curlsession, CURLOPT_REFERER, "");
       curl_setopt ($curlsession, CURLOPT_TIMEOUT, 3);
       $buffer = curl_exec ($curlsession);
       $cinfo = curl_getinfo($curlsession);
       curl_close($curlsession);
       if ($cinfo['http_code'] != 200) {
          return "";
       }
       return $buffer;
  }
}
?> 