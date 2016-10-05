<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Notification;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Notification クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2NotificationClient extends AbstractGs2Client {

	public static $ENDPOINT = 'notification';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param array $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * 通知リストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* notificationId => 通知ID
	 * 		* ownerId => オーナーID
	 * 		* name => 通知名
	 * 		* description => 説明文
	 * 		* createAt => 作成日時
	 * 		* updateAt => 更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeNotification($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Notification', 
					'DescribeNotification', 
					Gs2NotificationClient::$ENDPOINT, 
					'/notification',
					$query);
	}
	
	/**
	 * 通知を作成<br>
	 * <br>
	 * 通知はGS2内で発生したイベントを受け取る手段を提供します。<br>
	 * 例えば、GS2-Watch の監視データが一定の閾値を超えた時に通知する。といった用途に利用できます。<br>
	 * <br>
	 * GS2 のサービスの多くはクオータを買い、その範囲内でサービスを利用する形式が多く取られていますが、<br>
	 * 現在の消費クオータが GS2-Watch で取れますので、クオータの消費量が予約量の80%を超えたら通知をだす。というような使い方ができます。<br>
	 * 
	 * @param array $request
	 * * name => 通知名
	 * * description => 説明文
	 * @return array
	 * * item
	 * 	* notificationId => 通知ID
	 * 	* ownerId => オーナーID
	 * 	* name => 通知名
	 * 	* description => 説明文
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function createNotification($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		$query = [];
		return $this->doPost(
					'Gs2Notification', 
					'CreateNotification', 
					Gs2NotificationClient::$ENDPOINT, 
					'/notification',
					$body,
					$query);
	}

	/**
	 * 通知を取得
	 *
	 * @param array $request
	 * * notificationName => 通知名
	 * @return array
	 * * item
	 * 	* notificationId => 通知ID
	 * 	* ownerId => オーナーID
	 * 	* name => 通知名
	 * 	* description => 説明文
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 *
	 */
	public function getNotification($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Notification',
				'GetNotification',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName'],
				$query);
	}

	/**
	 * 通知を更新
	 *
	 * @param array $request
	 * * notificationName => 通知名
	 * * description => 説明文
	 * @return array 
	 * * item
	 * 	* notificationId => 通知ID
	 * 	* ownerId => オーナーID
	 * 	* name => 通知名
	 * 	* description => 説明文
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function updateNotification($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		$query = [];
		return $this->doPut(
				'Gs2Notification',
				'UpdateNotification',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName'],
				$body,
				$query);
	}
	
	/**
	 * 通知を削除
	 * 
	 * @param array $request
	 * * notificationName => 通知名
	 */
	public function deleteNotification($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Notification', 
					'DeleteNotification', 
					Gs2NotificationClient::$ENDPOINT, 
					'/notification/'. $request['notificationName'],
					$query);
	}
	
	/**
	 * 通知先リストを取得
	 *
	 * @param array $request
	 * * notificationName => 通知名
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* subscribeId => 通知先ID
	 * 		* notificationId => 通知ID
	 * 		* type => 通知プロトコル
	 * 		* endpoint => 通知先
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeSubscribe($request, $pageToken = NULL, $limit = NULL) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
				'Gs2Notification',
				'DescribeSubscribe',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName']. '/subscribe',
				$query);
	}
	
	/**
	 * 通知先を作成<br>
	 * <br>
	 * E-Mail, HTTP/HTTPS 通信を指定して通知先を登録できます。<br>
	 * 通知先は1つの通知に対して複数登録することもできます。<br>
	 * <br>
	 * そのため、メールとSlackに通知する。といった利用ができます。<br>
	 * <br>
	 * type に指定できるパラメータ<br>
	 * <ul>
	 * <li>email</li>
	 * <li>http/https</li>
	 * </ul>
	 * <br>
	 * endpoint には type に指定したプロトコルによって指定する内容が変わります。<br>
	 * email を選択した場合には メールアドレスを、<br>
	 * http/https を選択した場合には URL を指定してください。<br>
	 * <br>
	 * http/https を選択した場合には登録時に疎通確認を行います。<br>
	 * 指定したURLでPOSTリクエストを受け付けられる状態で登録してください。<br>
	 * 疎通確認の通信は通常の通知とは異なり、body パラメータがからの通信が発生します。ご注意ください。<br>
	 *
	 * @param array $request
	 * * notificationName => 通知名
	 * * name => 通知先名
	 * * type => 通知プロトコル
	 * * endpoint => 通知先
	 * @return array
	 * * item
	 * 	* subscribeId => 通知先ID
	 * 	* notificationId => 通知ID
	 * 	* type => 通知プロトコル
	 * 	* endpoint => 通知先
	 * 	* createAt => 作成日時
	 */
	public function createSubscribe($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('type', $request)) $body['type'] = $request['type'];
		if(array_key_exists('endpoint', $request)) $body['endpoint'] = $request['endpoint'];
		$query = [];
		return $this->doPost(
				'Gs2Notification',
				'CreateSubscribe',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName']. '/subscribe',
				$body,
				$query);
	}
	
	/**
	 * 通知先を取得
	 *
	 * @param array $request
	 * * notificationName => 通知名
	 * * subscribeId => 通知先ID
	 * @return array
	 * * item
	 * 	* subscribeId => 通知先ID
	 * 	* notificationId => 通知ID
	 * 	* type => 通知プロトコル
	 * 	* endpoint => 通知先
	 * 	* createAt => 作成日時
	 */
	public function getSubscribe($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		if(!array_key_exists('subscribeId', $request)) throw new NullPointerException();
		if(is_null($request['subscribeId'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Notification',
				'GetSubscribe',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName']. '/subscribe/'. $request['subscribeId'],
				$query);
	}
	
	/**
	 * 通知先を削除
	 * 
	 * @param array $request
	 * * notificationName => 通知名
	 * * subscribeId => 通知先ID
	 */
	public function deleteSubscribe($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('notificationName', $request)) throw new NullPointerException();
		if(is_null($request['notificationName'])) throw new NullPointerException();
		if(!array_key_exists('subscribeId', $request)) throw new NullPointerException();
		if(is_null($request['subscribeId'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
				'Gs2Notification',
				'DeleteSubscribe',
				Gs2NotificationClient::$ENDPOINT,
				'/notification/'. $request['notificationName']. '/subscribe/'. $request['subscribeId'],
				$query);
	}
}