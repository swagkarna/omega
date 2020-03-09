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

import re
from ui.color import colorize


class Url(str):
    """Http(s) url link. (extends str)

    Takes a string representation of an url link. it must start with
    http(s)://, domain can be an IP address or domain name.

    Example:
    >>> print(Url('google.fr'))
    "http://google.fr:80/"

    """

    _match_regexp = (r"^(?:(https?)?://)?([\w.-]{3,63})"
                     r"(?::(\d+))?(/.*?)?(?:\?(.+)?)?$")

    def __new__(cls, url):
        try:
            components = list(re.match(cls._match_regexp, url).groups())
        except:
            raise ValueError('«%s» is not a valid URL Link' % url)

        defaults = ['http', '', '80', '/', '']
        if components[0] and components[0].lower() == 'https':
            defaults[2] = '443'
        for index, elem in enumerate(components):
            if elem is None:
                components[index] = defaults[index]

        url = "{}://{}:{}{}".format(*tuple(components))
        if components[4]:
            url += "?" + components[4]

        return str.__new__(cls, url)

    def __init__(self, _):
        url = super().__str__()
        components = list(re.match(self._match_regexp, url).groups())
        self.scheme, self.host, self.port, self.path, self.query = components
        self.components = components

    def _raw_value(self):
        return super().__str__()

    def __call__(self):
        return self._raw_value()

    def __str__(self):
        return colorize('%Cyan', self.scheme, '://', '%BoldWhite', self.host,
                        '%BasicCyan', ':', self.port, self.path,
                        ("?" + self.query if self.query else ""))
