package devoir_v2.proxyPatternTest;

import javax.swing.JPanel;

public interface OfficePlayerIA {
	
	
	/**
	 * m�thode permettant de pallier l'absence de constructeur dans une interface
	 * @param s
	 */
	//public void init() throws Exception;
	
	
	/**
	 * m�thode r�cup�rant la valeur du texte
	 * @return
	 */
	//public String getTextButton() throws Exception;
	
	
	/**
	 * r�cup�re les vues sur nos figures
	 * @return
	 */
	//public ContainerViews getContainerViews() throws Exception;
	
	
	/**
	 * m�thode renvoyant une vue sur une figure
	 * @return
	 */
	public JPanel getPanel() throws Exception;
	
	public void play();
}