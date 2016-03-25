import requests


urlroot='https://api.thethings.io/v2/things/'
token='e5liLasnnRcHcaH6LUfKAWizB7w5PmecXtbLEiVUrmA'
header={'Accept': 'application/json', 'Content-Type': 'application/json'}
url=urlroot+token

def ttwrite(var, value, date):
	data='{"values":[{"key": "' + var + '","value": "' + value + '","datetime": "' + date + '"}]}'
	r = requests.post(url, headers=header, data=data)

