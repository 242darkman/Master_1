package devoir_v2.proxyPatternTest;


import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.util.Random;

import javax.swing.BoxLayout;
import javax.swing.JPanel;
import javax.swing.plaf.DimensionUIResource;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.listenerPattern.ContainerViews;
import devoir_v2.model.Circle;
import devoir_v2.model.Point;
import devoir_v2.model.Rectangle;
import devoir_v2.model.Triangle;
import devoir_v2.observerPattern.Memento;
import devoir_v2.statePattern.Context;
import devoir_v2.statePattern.FunctionalityPanel;
import devoir_v2.view.ScorePanel;



public class RealPlayerIA implements OfficePlayerIA {

	private ContainerShapes cs;
	
	
	private final static int BOUND = 400;
		
	private String current_player = "player_circle";
		
	private ContainerViews cv;
	
	private Graphics g;
	
	//private Graphics2D g2d;
	
	
	
	/**
	 * @param cs
	 */
	public RealPlayerIA(ContainerShapes cs) {
		this.cs = cs;
		this.cv = new ContainerViews(cs);
	}
	
	
	
	/**
	 * generate number between 100 and upper bound
	 * @param upBound
	 * @return
	 */
	public int generateInt(int upBound) {
		Random rand = new Random();
		return 100 + rand.nextInt(upBound - 100);
	}
	
	
	
	@Override
	public void play() {
		if (this.current_player.equals("player_circle")) {
			this.drawCircle();
			this.current_player = "player_triangle";
		}else if (this.current_player.equals("player_triangle")) {
			this.drawTriangle();
			this.current_player = "player_rectangle";
		}else if (this.current_player.equals("player_rectangle")) {
			this.drawRectangle();
			this.current_player = "player_circle";
		}
	}
	
	
	
	/**
	 * move the shape
	 */
	public void move() {
		for (int i = 0; i < this.cs.getShapes().size(); i++) {
			float x = this.cs.getShapes().get(i).getCenter().getX();
			float y = this.cs.getShapes().get(i).getCenter().getY();
			if (this.cs.getShapes().get(i).contains(new Point(x, y))) {
				this.cs.moveShape(this.cs.getShapes().get(i), x, y);
			}
		}
		Memento.addContainerShapes(this.cs);
	}
	
	
	
	/**
	 * create Circle for IA player
	 */
	public Circle createCircle() {
		float radius  = 170;
		Circle circle = new Circle( (float) this.generateInt(BOUND), (float) this.generateInt(BOUND), radius);
		this.cs.addShape(circle);
		Memento.addContainerShapes(this.cs);
		return circle;
	}
	
	
	
	public void drawCircle() {
		Circle c = this.createCircle();
		Graphics2D g2 = (Graphics2D) g;
		Graphics2D g2d = (Graphics2D) g2.create();
		//Graphics2D g2d = (Graphics2D) g;
		g2d = (Graphics2D) g2d.create();
		g2d.setPaint(Color.ORANGE);
		g2d.drawOval((int) (c.getCenter().getX() - (c.getRadius() / 2)),
				(int) (c.getCenter().getY() - (c.getRadius() / 2)), (int) c.getRadius(), (int) c.getRadius());
	}
	
	
	
	/**
	 * create Triangle for IA player
	 */
	public Triangle createTriangle() {
		float side = this.generateInt(250);
		Triangle triangle = new Triangle((float) this.generateInt(BOUND), (float) this.generateInt(BOUND), side);
		this.cs.addShape(triangle);
		Memento.addContainerShapes(this.cs);
		return triangle;
	}
	
	
	
	public void drawTriangle() {
		Triangle t = this.createTriangle();
		Graphics2D g2 = (Graphics2D) g;
		Graphics2D g2d = (Graphics2D) g2.create();
		g2d.setPaint(Color.GREEN);
		int[] x = { (int) t.getPoints()[0].getX(), (int) t.getPoints()[1].getX(), (int) t.getPoints()[2].getX() };
		int[] y = { (int) t.getPoints()[0].getY(), (int) t.getPoints()[1].getY(), (int) t.getPoints()[2].getY() };
		g2d.drawPolygon(x, y, 3);
	}
	
	
	
	/**
	 * create rectangle for IA player
	 */
	public Rectangle createRectangle() {
		float width = this.generateInt(150);
		float height = this.generateInt(300);
		Rectangle rectangle = new Rectangle((float) this.generateInt(BOUND), (float) this.generateInt(BOUND), height, width);
		this.cs.addShape(rectangle);
		Memento.addContainerShapes(this.cs);
		return rectangle;
	}
	
	
	
	public void drawRectangle() {
		Rectangle r = this.createRectangle();
		Graphics2D g2 = (Graphics2D) g;
		Graphics2D g2d = (Graphics2D) g2.create();
		int[] x = { (int) r.getPoints()[0].getX(), (int) r.getPoints()[1].getX(), (int) r.getPoints()[2].getX(), (int) r.getPoints()[3].getX() }; 
		int[] y = { (int) r.getPoints()[0].getY(), (int) r.getPoints()[1].getY(), (int) r.getPoints()[2].getY(), (int) r.getPoints()[3].getY() }; 
		g2d.drawPolygon(x, y, 4);
	}



	/**
	 * @return the cs
	 */
	public ContainerShapes getCs() {
		return cs;
	}





	@Override
	public JPanel getPanel() throws Exception {
		JPanel panel = new JPanel();
				
		ScorePanel score = new ScorePanel(this.cs);
		Context context = new Context(); // setting the context
		@SuppressWarnings("unused")
		JPanel fp = new FunctionalityPanel(context, this.cs);
		this.cv.setC(context);
		//this.cv.setSp(score);
		score.setMaximumSize(new DimensionUIResource(400, 300));
		this.cv.setMaximumSize(new DimensionUIResource(400, 300));
		this.cs.addChangeListener(this.cv);
		panel.setLayout(new BoxLayout(panel, BoxLayout.Y_AXIS));
		
		 
		 // add elements into the panel
		panel.add(this.cv);
		//panel.add(fp);
		panel.add(score);
		
        
		return panel;
	}



	
	
	/**
	 * @return the cv
	 */
	public ContainerViews getCv() {
		return cv;
	}




	
	
}
