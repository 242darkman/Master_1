package devoir_v2.proxyPatternTest;

import javax.swing.JPanel;

public interface OfficePlayerIA {
	
	
	/**
	 * méthode permettant de pallier l'absence de constructeur dans une interface
	 * @param s
	 */
	//public void init() throws Exception;
	
	
	/**
	 * méthode récupérant la valeur du texte
	 * @return
	 */
	//public String getTextButton() throws Exception;
	
	
	/**
	 * récupère les vues sur nos figures
	 * @return
	 */
	//public ContainerViews getContainerViews() throws Exception;
	
	
	/**
	 * méthode renvoyant une vue sur une figure
	 * @return
	 */
	public JPanel getPanel() throws Exception;
	
	public void play();
}