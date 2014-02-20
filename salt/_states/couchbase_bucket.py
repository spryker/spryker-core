# -*- coding: utf-8 -*-
'''
Support for Couchbase Administration
'''

# Import python libs
import logging
import salt.utils
import string

log = logging.getLogger(__name__)

# @TODO: bucket-settings like password, type, port and so on
# @TODO: add documentation
def present(name, 
			user=None,
			password=None,
			server=None,
			binary=None,
			size=None,
			replica=None,
			bucket_password=None,
			**kwargs):

	ret = {'name': name,
			'changes': {},
			'result': False,
			'comment': ''
	}

	if _checkBucketExists(name, server, user, password, binary):
		ret['result'] = True
		ret['comment'] = 'Bucket "{0}" already exists'.format(name)
		return ret


	options = ['--bucket=' + name]

	if size is not None:
		options.append('--bucket-ramsize=' + str(size))

	if replica is not None:
		options.append('--bucket-replica=' + str(replica))

	if bucket_password is not None:
		options.append('--bucket-password=' + str(bucket_password))
		
	options.append('--bucket-type=couchbase')
	options.append('--enable-flush=1')

	ret = __salt__['couchbase.cli']('bucket-create', server, user, password, binary, options)

	ret['name'] = name

	if ret['result']:
		ret['comment'] = 'Bucket "{0}" successfully created'.format(name)

	return ret


# @TODO: add documentation
def absent(name,
			user=None,
			password=None,
			server=None,
			binary=None,
			**kwargs):

	ret = {'name': name,
			'changes': {},
			'result': False,
			'comment': ''
	}

	if not _checkBucketExists(name, server, user, password, binary):
		ret['result'] = True
		ret['comment'] = 'Bucket "{0}" does not exist'.format(name)
		return ret

	options = ['--bucket=' + name]

	ret = __salt__['couchbase.cli']('bucket-delete', server, user, password, binary, options)

	ret['name'] = name

	if ret['result']:
		ret['comment'] = 'Bucket "{0}" successfully deleted'.format(name)

	return ret


# @TODO: add documentation
def _checkBucketExists(bucket, 		
		server=None,
		user=None,
		password=None,
		binary=None):
	
	options = ['| egrep "^' + bucket + '$"']

	ret = __salt__['couchbase.cli']('bucket-list', server, user, password, binary, options)

	return ret['result']
