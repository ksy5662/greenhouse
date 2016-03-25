import time
import datetime
from thethingsAPI import thethingsiO
from ttrest import ttwrite

 
sensorids = ["28-0316004d7fff", "28-0316005ec0ff"]
avgtemperatures = []
for sensor in range(len(sensorids)):
	temperatures = []
	for polltime in range(0,3):
			text = '';
			while text.split("\n")[0].find("YES") == -1:
					tfile = open("/sys/bus/w1/devices/"+ sensorids[sensor] +"/w1_slave")
					text = tfile.read()
					tfile.close()
					time.sleep(1)
 
			secondline = text.split("\n")[1]
			temperaturedata = secondline.split(" ")[9]
			temperature = float(temperaturedata[2:])
			temperatures.append(temperature / 1000)
 
	avgtemperatures.append(sum(temperatures) / float(len(temperatures)))

print avgtemperatures[0]
print avgtemperatures[1]
theThing = thethingsiO()
print theThing.dt2strnormal(datetime.datetime.now())
ttwrite("dryTemp",str(avgtemperatures[0]),theThing.dt2strnormal(datetime.datetime.now()))