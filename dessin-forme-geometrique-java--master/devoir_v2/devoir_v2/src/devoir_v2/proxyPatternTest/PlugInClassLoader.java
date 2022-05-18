
package devoir_v2.proxyPatternTest;

import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.net.URLClassLoader;
import java.util.jar.JarFile;

public class PlugInClassLoader {

	private final static String PLUGIN_CLASS_ATTRIBUTE = "Data-PlugIn";

	public OfficePlayerIA loadJarPlugIn(File file) throws PlugInLoadingException {
		try {

			// récupération du nom de la classe du plug-in dans le manifest du jar :
			JarFile jarFile = new JarFile(file);
			String plugInClassName = jarFile.getManifest().getMainAttributes().getValue(PLUGIN_CLASS_ATTRIBUTE);
			jarFile.close();

			// création d'un classloader branché sur notre jar (via une instance d'url)
			URL[] jarURLs = new URL[1];
			jarURLs[0] = file.toURL();
			ClassLoader classLoader = new URLClassLoader(jarURLs, ClassLoader.getSystemClassLoader());

			// Chargement de la classe dans la machine virtuelle (instance de Class) :
			Class plugInClass = classLoader.loadClass(plugInClassName);

			// Obtention d'une instance à partir de l'instance de classe, et transtypage car
			// reçue comme Object :
			OfficePlayerIA plugIn = (OfficePlayerIA) plugInClass.newInstance();

			return plugIn;
		}

		catch (IOException | ClassNotFoundException | InstantiationException | IllegalAccessException ex) {
			throw new PlugInLoadingException(ex);
		}
	}
}
