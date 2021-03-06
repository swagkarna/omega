#!/usr/bin/env python3

#            ---------------------------------------------------
#                              Omega Framework                                
#            ---------------------------------------------------
#                  Copyright (C) <2020>  <Entynetproject>       
#
#        This program is free software: you can redistribute it and/or modify
#        it under the terms of the GNU General Public License as published by
#        the Free Software Foundation, either version 3 of the License, or
#        any later version.
#
#        This program is distributed in the hope that it will be useful,
#        but WITHOUT ANY WARRANTY; without even the implied warranty of
#        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
#        GNU General Public License for more details.
#
#        You should have received a copy of the GNU General Public License
#        along with this program.  If not, see <http://www.gnu.org/licenses/>.

"""Omega plugin developer API

This package includes the python-side plugin
development API.

CONTENTS:

* plugin (type: api.plugin.Plugin())
    Access running plugin attributes

* environ (type: dict)
    Access omega session's Environment Variables

* server (type: package)
    Provides target server related operations.
    Modules:
      - path: Remote server pathname operations.
      - payload: Run a PHP payload on remote server.
"""
__all__ = ["plugin", "environ", "server"]

from core import session

# Define api.plugin (current plugin attributes)
from .plugin import plugin

# Import api.server package
from . import server

# Define api.environ dictionary (environment variables)
environ = session.Env
del session
