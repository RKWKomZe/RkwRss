config.tx_extbase.persistence {
	classes {
		RKW\RkwResourcespace\Domain\Model\File {
			mapping {
				tableName = sys_file
				identifier = identifier
			}
		}
		RKW\RkwResourcespace\Domain\Model\FileReference {
			mapping {
				tableName = sys_file_reference
				columns {
					uid_local.mapOnProperty = file
				}
			}
		}
		RKW\RkwResourcespace\Domain\Model\FileMetadata {
			mapping {
				tableName = sys_file_metadata
				newRecordStoragePid = 0
				columns {
					file.mapOnProperty = file
					keywords.mapOnProperty = keywords
				}
			}
		}
		RKW\RkwResourcespace\Domain\Model\MediaSources {
			mapping {
				tableName = tx_rkwbasics_domain_model_mediasources
			}
		}
		RKW\RkwResourcespace\Domain\Model\BackendUser {
			mapping {
				tableName = be_users
				columns {
					allowed_languages.mapOnProperty = allowedLanguages
					file_mountpoints.mapOnProperty = fileMountPoints
					db_mountpoints.mapOnProperty = dbMountPoints
					usergroup.mapOnProperty = backendUserGroups
				}
			}
		}
	}
	# make sure ref_index is updated
	updateReferenceIndex = 1
}


plugin.tx_rkwresourcespace_import {
	view {
		templateRootPaths.0 = EXT:rkw_resourcespace/Resources/Private/Templates/
		templateRootPaths.1 = {$plugin.tx_rkwresourcespace_import.view.templateRootPath}
		partialRootPaths.0 = EXT:rkw_resourcespace/Resources/Private/Partials/
		partialRootPaths.1 = {$plugin.tx_rkwresourcespace_import.view.partialRootPath}
		layoutRootPaths.0 = EXT:rkw_resourcespace/Resources/Private/Layouts/
		layoutRootPaths.1 = {$plugin.tx_rkwresourcespace_import.view.layoutRootPath}
	}
	persistence {
		storagePid = {$plugin.tx_rkwresourcespace_import.persistence.storagePid}
		#recursive = 1
	}
	features {
		#skipDefaultArguments = 1
	}
	mvc {
		#callDefaultActionIfActionCantBeResolved = 1
	}
	settings {
		uploadDestination = {$plugin.tx_rkwresourcespace_import.settings.uploadDestination}
		localBufferDestination = {$plugin.tx_rkwresourcespace_import.settings.localBufferDestination}
		ipRestriction = {$plugin.tx_rkwresourcespace_import.settings.ipRestriction}
		logActivitiesInDb = {$plugin.tx_rkwresourcespace_import.settings.logActivitiesInDb}
		backendLoginIsMandatory = {$plugin.tx_rkwresourcespace_import.settings.backendLoginIsMandatory}
		enableFormUpload = {$plugin.tx_rkwresourcespace_import.settings.enableFormUpload}
		resourceSpaceApi {
			baseUrl = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.baseUrl}
			user = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.user}
			password = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.password}
			privateKey = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.privateKey}
            privateKey = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.privateKey}
            proxy = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.proxy}
            proxyUsername = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.proxyUsername}
            proxyPassword = {$plugin.tx_rkwresourcespace_import.settings.resourceSpaceApi.proxyPassword}
        }
		upload {
			sysFileStorageUid = {$plugin.tx_rkwresourcespace_import.settings.upload.sysFileStorageUid}
			processing = {$plugin.tx_rkwresourcespace_import.settings.upload.processing}
			processing {
				forceFormat = {$plugin.tx_rkwresourcespace_import.settings.upload.processing.forceFormat}
				maxWidth = {$plugin.tx_rkwresourcespace_import.settings.upload.processing.maxWidth}
			}
		}
	}
}
