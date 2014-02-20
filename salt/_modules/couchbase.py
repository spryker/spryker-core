# -*- coding: utf-8 -*-
'''
Module for Couchbase Interaction
'''

import salt.utils
import string

# @TODO: add documentation
def cli(action,
		server=None,
		user=None,
		password=None,
		binary=None,
		options=None):

	ret = {	'changes': {},
			'result': False,
			'comment': ''
	}

	if server is None:
		server = '127.0.0.1:8091'

	if binary is None:
		binary = '/opt/couchbase/bin/couchbase-cli'

	command = [binary, action, '-c ', server]

	if user is not None:
		command.append('-u')
		command.append(user)

	if password is not None:
		command.append('-p')
		command.append(password)

	if options is not None:
		command.extend(options)

	try:
		cmd = __salt__['cmd.run_all'](string.join(command, ' '))
	except CommandExecutionError as err:
		ret['comment'] = str(err)
		return ret

	ret['result'] = not bool(cmd['retcode'])
	ret['changes'] = {'call': cmd['stdout']}

	if not ret['result']:
		ret['comment'] = cmd['stdout']
	
	return ret