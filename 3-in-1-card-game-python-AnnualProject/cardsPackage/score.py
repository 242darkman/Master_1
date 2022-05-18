import pygame as pg


class Score:

    def __init__(self, screen, width, height):
        self.screen = screen
        self.black = (0, 0, 0)
        self.width = width
        self.height = height

    def text_objects(self, text, font):
        textSurface = font.render(text, True, self.black)
        return textSurface, textSurface.get_rect()

    def message_display(self, text):
        text_size = pg.font.Font('resources/font/FreeSansBoldOblique.ttf', 35)
        TextSurf, TextRect = self.text_objects("Score = " + str(text), text_size)
        # TextRect.center = ((display_width / 2), (display_height / 2))
        TextRect.center = (self.width, self.height)
        self.screen.blit(TextSurf, TextRect)
        # pg.display.update()
