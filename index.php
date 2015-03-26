
		<?php 
			header("Content-type: text/xml");
			$comSym=$_GET["company"];
		    $url="http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22";
		    $url.=$comSym;
		    $url.="%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
			$newsurl="http://feeds.finance.yahoo.com/rss/2.0/headline?s=".$comSym."&region=US&lang=en-US";
		    $xml = simplexml_load_file($url);
			$change=$xml->results->quote->Change;
			$newsxml=simplexml_load_file($newsurl);
			$xmltext="";
		    if($change!=''){ 
				$name=($xml->results->quote->Name); 
				$type=substr($change,0,1);
				$percent=$xml->results->quote->ChangeinPercent;
				$lastPrice=$xml->results->quote->LastTradePriceOnly;
				$previous=$xml->results->quote->PreviousClose;
				$dayslow=$xml->results->quote->DaysLow;
				$dayshigh=$xml->results->quote->DaysHigh;
				$open=$xml->results->quote->Open;
				$yearlow=$xml->results->quote->YearLow;
				$yearhigh=$xml->results->quote->YearHigh;
				$bid=$xml->results->quote->Bid;
				$volumn=$xml->results->quote->Volume;
				$ask=$xml->results->quote->Ask;
				$averagevol=$xml->results->quote->AverageDailyVolume;
				$oneyear=$xml->results->quote->OneyrTargetPrice;
				$market=$xml->results->quote->MarketCapitalization;
			
				$xmltext.="<result>";
				$xmltext.="<Name>".$name."</Name>";
				$xmltext.="<Symbol>".$comSym."</Symbol>";
				$xmltext.="<quote>";
				$xmltext.="<ChangeType>".$type."</ChangeType>";
				$xmltext.="<Change>".number_format(substr($change,1),2)."</Change>";
				$xmltext.="<ChangeInPercent>";
				if($change!=0){ $xmltext.=substr($percent,1); }
				else {$xmltext.=$percent; }   $xmltext.="</ChangeInPercent>";
				$xmltext.="<LastTradePriceOnly>".number_format(floatval($lastPrice),2)."</LastTradePriceOnly>";
				$xmltext.="<PreviousClose>";
				if($previous!=''){ $xmltext.=number_format(floatval($previous),2);}
				else {$xmltext.=$previous;} $xmltext.="</PreviousClose>";
				$xmltext.="<DaysLow>";
				if($dayslow!='') { $xmltext.=number_format(floatval($dayslow),2); }
				else { $xmltext.=$dayslow; } $xmltext.="</DaysLow>";
				$xmltext.="<DaysHigh>";
				if($dayshigh!='') { $xmltext.=number_format(floatval($dayshigh),2); }
				else { $xmltext.=$dayshigh; } $xmltext.="</DaysHigh>";
				$xmltext.="<Open>";
				if($open!='') { $xmltext.=number_format(floatval($open),2); }
				else { $xmltext.=$open; } $xmltext.="</Open>";
				$xmltext.="<YearLow>";
				if($yearlow!='') { $xmltext.=number_format(floatval($yearlow),2); }
				else { $xmltext.=$yearlow; } $xmltext.="</YearLow>";
				$xmltext.="<YearHigh>";
				if($yearhigh!='') { $xmltext.=number_format(floatval($yearhigh),2); }
				else { $xmltext.=$yearhigh; } $xmltext.="</YearHigh>";
				$xmltext.="<Bid>";
				if($bid!='') { $xmltext.=number_format(floatval($bid),2); }
				else { $xmltext.=$bid; } $xmltext.="</Bid>";
				$xmltext.="<Volumn>".number_format(floatval($volumn))."</Volumn>";
				$xmltext.="<Ask>";
				if($ask!='') { $xmltext.=number_format(floatval($ask),2); }
				else { $xmltext.=$ask; } $xmltext.="</Ask>";
				$xmltext.="<AverageDailyVolumn>".number_format(floatval($averagevol))."</AverageDailyVolumn>";
				$xmltext.="<OneYearTargetPrice>";
				if($oneyear!='') { $xmltext.=number_format(floatval($oneyear),2); }
				else { $xmltext.=$oneyear; } $xmltext.="</OneYearTargetPrice>";
				$xmltext.="<MarketCapitalization>";
				if($market!='') { $xmltext.=number_format(floatval($market),1).substr($market,-1); }
				else { $xmltext.=$market; } $xmltext.="</MarketCapitalization>";
				$xmltext.="</quote>";
				$xmltext.="<News>";
				$title=$newsxml->channel->item->title;
				if($title=="Yahoo! Finance: RSS feed not found"){
					$xmltext.="<Item><Title>"."Financial Company News is not available"."</Title></Item>";
				}
				else{
					
					foreach($newsxml->channel->item as $item){
						$xmltext.="<Item>";
                        $xmltext.="<Title>".htmlspecialchars(strip_tags($item->title))."</Title><Link>".htmlspecialchars(strip_tags($item->link), ENT_QUOTES)."</Link>";
						$xmltext.="</Item>";
                    }
					unset($item);
				}
				$xmltext.="</News><StockChartImageURL>";
				$xmltext.="http://chart.finance.yahoo.com/t?s=".$comSym."&amp;lang=en-US&amp;amp;width=300&amp;height=180";
				$xmltext.="</StockChartImageURL>";
				$xmltext.="</result>";
				echo $xmltext;
			} 
			else{
				$xmltext.="<result><Name>".$change."</Name></result>";
				echo $xmltext;
			}
			?>
			
