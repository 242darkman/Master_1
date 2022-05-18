package devoir_v2.proxyPatternTest;


import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JPanel;

import devoir_v2.listenerPattern.ContainerShapes;

public class ProxyPlayerIA implements OfficePlayerIA {
	
	private ContainerShapes cs;
	
	private RealPlayerIA real_player;
	


	/**
	 * @param cs
	 */
	public ProxyPlayerIA(ContainerShapes cs) {
		this.cs = cs;
		this.real_player = new RealPlayerIA(cs);
	}



	



	public void init() throws Exception {
		new RealPlayerIA(this.cs);
	}



	@Override
	public JPanel getPanel() throws Exception {
		JPanel pan = this.real_player.getPanel();
		JButton play = new JButton("Play with IA");     
		 play.setBounds(50,100,80,30);
		 play.setForeground(Color.darkGray);
		 play.setBackground(Color.cyan); 
		 play.addActionListener(new ActionListener() {

			@Override
			public void actionPerformed(ActionEvent e) {
				play();
			}
			 
		 });
		 
		 pan.add(play);
		 
		return pan;
	}







	@Override
	public void play() {
		while ( !this.cs.won() ) {
			//this.real_player = new RealPlayerIA(this.cs);
			this.real_player.play();
		}
	}





}
