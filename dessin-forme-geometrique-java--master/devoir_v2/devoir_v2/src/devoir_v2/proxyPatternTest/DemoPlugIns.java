/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package devoir_v2.proxyPatternTest;

import java.awt.Container;
import java.awt.GridLayout;
import java.io.File;
import java.io.FileFilter;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Random;

import javax.swing.JFrame;
import javax.swing.WindowConstants;

import devoir_v2.listenerPattern.ContainerShapes;





@SuppressWarnings("serial")
public class DemoPlugIns extends JFrame
{

    public DemoPlugIns() throws Exception
    {
	super("Demo Plug-ins");
	this.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
	Random rand = new Random();
	int pts = rand.nextInt(125);
	OfficePlayerIA data = new ProxyPlayerIA( new ContainerShapes(pts, (3 * 1200) / 4, (3 * 700) / 4, 20) );
	Container cp = this.getContentPane();

	System.out.println("N'avons-nous pas de plugins à charger ? " + loadPlugins(data).isEmpty());
	ArrayList<ProxyPlayerIA> plugins = loadPlugins(data);
	setLayout(new GridLayout(1,plugins.size()));
	for (int i = 0; i < plugins.size(); i++)
	    cp.add(plugins.get(i).getPanel());
		System.out.println("Notre liste de plugins est-elle vide ? " + plugins.isEmpty());
	this.pack();
	this.setVisible(true);
    }

    
    
    
    public ArrayList<ProxyPlayerIA> loadPlugins(OfficePlayerIA data) throws Exception
    {

	ArrayList<ProxyPlayerIA> plugins = new ArrayList<>();


	String locationOfDemoJar = this.getClass().getProtectionDomain().getCodeSource().getLocation().getPath();
	locationOfDemoJar = locationOfDemoJar.substring(0, locationOfDemoJar.lastIndexOf("/"));
	String locationOfPlugins = locationOfDemoJar + "/drawShape";
	System.out.println("- Looking for plugins in : " + locationOfPlugins);
	URL pluginsDirectory;
	try
	{
	    pluginsDirectory = new File(locationOfPlugins).toURI().toURL();
	}
	catch (MalformedURLException ex)
	{
	    ex.printStackTrace();
	    return plugins;
	}
	if (pluginsDirectory == null)
	{
	    return plugins;
	}
	File pluginDir = new File(pluginsDirectory.getFile());
	File[] files = pluginDir.listFiles(new FileFilter()
	{
	    @Override
	    public boolean accept(File file)
	    {
		return file.getName().endsWith("jar");
	    }

	});
	if (files == null)
	{
	    return plugins;
	}
	else
	{
	    PlugInClassLoader pcl = new PlugInClassLoader();
	    for (int i = 0; i < files.length; i++)
	    {
		File jarFile = files[i];
		//String fileName = jarFile.toString();
		ProxyPlayerIA plug;
		try
		{
		    plug = (ProxyPlayerIA) pcl.loadJarPlugIn(jarFile);
		    System.out.println("- Plugin found :" + plug.getClass().getSimpleName());
		    System.out.println("data="+data);
		    //plug.init(data);
		    plug.init();
		    plugins.add(plug);
		}
		catch (PlugInLoadingException ex)
		{
		    System.out.println("Le plug-in du fichier " + jarFile + " n'a pu être chargé, voir exception ci-dessous");
		    ex.printStackTrace();
		}

	    }
	    return plugins;
	}
    }

    /**
     * @param args the command line arguments
     * @throws Exception 
     */
    public static void main(String[] args) throws Exception
    {
	new DemoPlugIns();
	// TODO code application logic here
    }

}
