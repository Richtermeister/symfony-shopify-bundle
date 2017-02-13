<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;

class AssetEndpoint extends AbstractEndpoint
{
	public function findByTheme($themeId, array $fields = array())
	{
		$params = array();

		if ($fields) {
			$params['fields'] = implode(',', $fields);
		}

		$request = new GetJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $response->get('assets');
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 * @return GenericEntity
	 */
	public function findOne($themeId, $assetPath)
	{
		$params = array(
			'asset[key]' => $assetPath,
			'theme_id'   => $themeId
		);

		$request = new GetJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('asset'));
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 * @param string $templateContent
	 * @return GenericEntity
	 */
	public function uploadTemplate($themeId, $assetPath, $templateContent)
	{
		$params = array(
			'asset' => array(
				'key'   => $assetPath,
				'value' => $templateContent
			)
		);

		$request = new PutJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('asset'));
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 * @param string $binary
	 * @return GenericEntity
	 */
	public function uploadBinaryFile($themeId, $assetPath, $binary)
	{
		$params = array(
			'asset' => array(
				'key'   => $assetPath,
				'attachment' => base64_encode($binary)
			)
		);

		$request = new PutJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('asset'));
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 * @param string $remoteFileUrl
	 * @return GenericEntity
	 */
	public function uploadRemoteFile($themeId, $assetPath, $remoteFileUrl)
	{
		$params = array(
			'asset' => array(
				'key' => $assetPath,
				'src' => $remoteFileUrl
			)
		);

		$request = new PutJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('asset'));
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 * @param string $copySourcePath
	 * @return GenericEntity
	 */
	public function copy($themeId, $assetPath, $copySourcePath)
	{
		$params = array(
			'asset' => array(
				'key'        => $assetPath,
				'source_key' => $copySourcePath
			)
		);

		$request = new PutJson('/admin/themes/' . $themeId . '/assets.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('asset'));
	}

	/**
	 * @param int $themeId
	 * @param string $assetPath
	 */
	public function delete($themeId, $assetPath)
	{
		$params = array(
			'asset[key]' => $assetPath,
			'theme_id'   => $themeId
		);

		$request = new DeleteParams('/admin/themes/' . $themeId . '/assets.json', $params);
		$this->send($request);
	}
}