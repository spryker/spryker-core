# -*- coding: utf-8 -*-
'''
Support for Couchbase Administration
'''

# Import python libs
import logging
import salt.utils
import string

def add_host(name, 
			user=None,
			password=None,
			server=None,
			binary=None,
			rebalance=True,
			**kwargs):

	ret = {'name': name,
			'changes': {},
			'result': False,
			'comment': ''
	}

	if _checkHostIsInCluster(name, server, user, password, binary):
		ret['result'] = True
		ret['comment'] = 'Host "{0}" is already in cluster'.format(name)
		return ret


	options = ['--server-add=' + name]

	options.append('--server-add-username=' + user)
	options.append('--server-add-password=' + password)

	command = 'server-add'

	if rebalance:
		command = 'rebalance'

	ret = __salt__['couchbase.cli'](command, server, user, password, binary, options)

	ret['name'] = name

	if ret['result']:
		ret['comment'] = 'Host "{0}" successfully added to cluster'.format(name)

	return ret



def _checkHostIsInCluster(name,
		server=None,
		user=None,
		password=None,
		binary=None):
	
	options = ['| egrep "' + name + '"']

	ret = __salt__['couchbase.cli']('server-list', server, user, password, binary, options)

	return ret['result']
