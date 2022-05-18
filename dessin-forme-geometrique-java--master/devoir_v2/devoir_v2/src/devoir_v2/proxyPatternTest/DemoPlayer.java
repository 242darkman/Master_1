package devoir_v2.proxyPatternTest;

import java.util.Random;

import javax.swing.JFrame;

import devoir_v2.listenerPattern.ContainerShapes;

public class DemoPlayer {

	public static void main(String[] args) throws Exception {
		
		JFrame frame = new JFrame("Demo player IA");
		Random rand = new Random();
		int pts = rand.nextInt(125);
		ContainerShapes cs = new ContainerShapes(pts, (3 * 1200) / 4, (3 * 700) / 4, 20);
		OfficePlayerIA op = new ProxyPlayerIA(cs);
		
		frame.add(op.getPanel());
		frame.setSize(1200,700);
		frame.setVisible(true);
		//frame.pack();
		frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
	}

}
