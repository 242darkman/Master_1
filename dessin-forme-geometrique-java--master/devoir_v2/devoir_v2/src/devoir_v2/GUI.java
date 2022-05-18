package devoir_v2;

import java.awt.Dimension;

import javax.swing.BoxLayout;
import javax.swing.JFrame;
import javax.swing.JPanel;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.listenerPattern.ContainerViews;
import devoir_v2.observerPattern.Memento;
import devoir_v2.statePattern.Context;
import devoir_v2.statePattern.FunctionalityPanel;
import devoir_v2.view.ScorePanel;

public class GUI { // our graphical user interface

	JFrame frame;

	public GUI() {
		this.frame = new JFrame();
		initUI();
	}

	private void initUI() {

		// Initializing the window height and length

		int window_width = 1300;
		int window_height = 750;

		// Initializing the container with a 101 points
		ContainerShapes cs = new ContainerShapes(101, (3 * window_width) / 4, (3 * window_height) / 4, 20);
		// adding the original state to the Memento
		Memento.addContainerShapes(cs);

		final ContainerViews surface = new ContainerViews(cs);

		Context context = new Context(); // setting the context
		surface.c = context;
		cs.addChangeListener(surface);

		surface.cs = cs;
		surface.addMouseListener(surface);
		surface.addMouseMotionListener(surface);

		// the score panel
		ScorePanel score = new ScorePanel(cs);
		score.setMaximumSize(new Dimension(window_width, window_height / 10));
		surface.score_panel = score;

		// the main panel that has all three panels
		JPanel mainPanel = new JPanel();
		// FunctionalityPanel
		JPanel fp = new FunctionalityPanel(context, cs);
		fp.setMaximumSize(new Dimension(window_width, window_height / 10));

		surface.setMaximumSize(new Dimension((3 * window_width) / 4, (3 * window_height) / 4));

		mainPanel.setLayout(new BoxLayout(mainPanel, BoxLayout.Y_AXIS));

		mainPanel.add(fp);
		mainPanel.add(surface); // adding all of the panels to the main panel
		mainPanel.add(score);
		frame.add(mainPanel); // adding the main panel to the frame

		frame.setTitle("Points");

		// Frame stuff

		frame.setSize(window_width, window_height);
		frame.setResizable(false);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	}

}